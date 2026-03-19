<?php

namespace App\Exports;

use App\Models\DebtPayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DebtPaymentReportExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize, 
    WithStyles,
    WithCustomStartCell,
    WithEvents
{
    protected $startDate;
    protected $endDate;
    
    // Variables Tracking
    private $no = 1;
    private $currentRow = 6; // Data dimulai dari baris 6
    private $lastPaymentId = null;
    private $dateHeaderRows = []; // Array untuk menyimpan nomor baris tanggal

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function collection()
    {
        $query = DebtPayment::with(['supplier', 'details.purchaseTransaction', 'cashAccount']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('payment_date', [$this->startDate, $this->endDate]);
        }

        // Urutkan berdasarkan tanggal agar grouping berfungsi
        $payments = $query->orderBy('payment_date', 'asc')->get();
        
        $flattened = collect();
        $lastDate = null;
        
        foreach($payments as $payment) {
            
            // 1. CEK PERUBAHAN TANGGAL (Untuk Header Hijau)
            // Jika tanggal saat ini beda dengan tanggal sebelumnya, buat baris Header
            $currentDate = date('Y-m-d', strtotime($payment->payment_date));
            
            if ($currentDate !== $lastDate) {
                // Push baris khusus untuk Header Tanggal
                $flattened->push([
                    'is_date_header' => true,
                    'date_value' => $payment->payment_date
                ]);
                $lastDate = $currentDate;
            }

            // 2. MASUKKAN DETAIL TRANSAKSI
            foreach($payment->details as $detail) {
                $row = new \stdClass();
                $row->payment = $payment;
                $row->detail = $detail;
                $flattened->push($row);
            }
            
            // 3. MASUKKAN BARIS TOTAL PER BUKTI
            $rowTotal = new \stdClass();
            $rowTotal->is_total_row = true;
            $rowTotal->payment = $payment;
            $flattened->push($rowTotal);
        }

        return $flattened;
    }

    public function map($row): array
    {
        // KONDISI 1: JIKA INI ADALAH BARIS HEADER TANGGAL
        if (is_array($row) && isset($row['is_date_header'])) {
            // Simpan nomor baris ini untuk diwarnai hijau nanti
            $this->dateHeaderRows[] = $this->currentRow;
            $this->currentRow++;
            
            // Reset Nomor Urut setiap ganti tanggal (Opsional, sesuai kebiasaan)
            $this->no = 1; 

            return [
                date('d-m-Y', strtotime($row['date_value'])), // Tampilkan Tanggal di Kolom A
                '', '', '', '', '', '', '', '', '' // Sisanya kosong
            ];
        }

        // KONDISI 2: JIKA BARIS TOTAL PER BUKTI
        if (isset($row->is_total_row)) {
            $this->currentRow++;
            return [
                '', '', '', '', '', '', '', 'TOTAL', '', $row->payment->total_amount
            ];
        }

        // KONDISI 3: DATA DETAIL NORMAL
        $payment = $row->payment;
        $detail = $row->detail;
        $trx = $detail->purchaseTransaction;

        // Cek Grouping ID untuk penomoran
        $isNewGroup = ($payment->id !== $this->lastPaymentId);
        $colNo = $isNewGroup ? $this->no++ : ''; // Nomor hanya muncul di baris pertama bukti kas
        
        $this->lastPaymentId = $payment->id;
        $this->currentRow++;

        return [
            $colNo,
            $payment->payment_number, 
            date('d-m-Y', strtotime($trx->purchase_date)), 
            $trx->purchase_code, 
            $trx->supplier_invoice_number ?? '-', 
            $payment->supplier->name, 
            $payment->check_number ?? '-', 
            $payment->check_date ? date('d-m-Y', strtotime($payment->check_date)) : '-', 
            $payment->cashAccount->name ?? 'CASH', 
            $detail->amount_paid 
        ];
    }

    public function headings(): array
    {
        return [
            'NO', 'NO. KAS', 'TGL. FAKTUR', 'NO. FAKTUR', 'NO. NOTA', 
            'NAMA SUPPLIER', 'NO. CEK', 'TGL. CAIR', 'NAMA AKUN', 'JUMLAH'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            5 => [ // Header Table
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            'J' => ['numberFormat' => ['formatCode' => '#,##0.00']], // Format Uang
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // JUDUL LAPORAN
                $sheet->mergeCells('A1:J1'); 
                $sheet->setCellValue('A1', 'DAFTAR PELUNASAN UTANG');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'underline' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                $highestRow = $sheet->getHighestRow();
                
                // BORDER UNTUK SEMUA DATA
                $sheet->getStyle('A5:J' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // --- STYLING HEADER TANGGAL (HIJAU) ---
                foreach ($this->dateHeaderRows as $rowNum) {
                    // Merge dari A sampai J
                    $sheet->mergeCells('A' . $rowNum . ':J' . $rowNum);
                    
                    // Style Hijau Muda & Bold
                    $sheet->getStyle('A' . $rowNum)->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E2EFDA'] // Warna Hijau Muda (Excel Light Green)
                        ],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    ]);
                }

                // --- STYLING BARIS TOTAL ---
                for ($row = 6; $row <= $highestRow; $row++) {
                    // Cek jika kolom H isinya "TOTAL"
                    $val = $sheet->getCell('H' . $row)->getValue();
                    if ($val === 'TOTAL') {
                        $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                            'font' => ['bold' => true],
                            'borders' => ['top' => ['borderStyle' => Border::BORDER_MEDIUM]]
                        ]);
                    }
                }
            },
        ];
    }
}