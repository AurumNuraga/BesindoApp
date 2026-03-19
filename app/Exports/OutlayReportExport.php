<?php

namespace App\Exports;

use App\Models\CashOutlay;
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

class OutlayReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithCustomStartCell, WithEvents
{
    protected $startDate;
    protected $endDate;
    private $no = 1;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                // 1. JUDUL LAPORAN (Baris 1-3)
                $sheet->mergeCells('A1:G1');
                $sheet->setCellValue('A1', 'PT. BESINDO JAYA ABADI');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                $sheet->mergeCells('A2:G2');
                $sheet->setCellValue('A2', 'LAPORAN PENGELUARAN KAS');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FF0000']], // Merah
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A3:G3');
                $sheet->setCellValue('A3', "Periode: " . date('d-m-Y', strtotime($this->startDate)) . " s/d " . date('d-m-Y', strtotime($this->endDate)));
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ---------------------------------------------------------
                // --- BARU: MENAMBAHKAN BARIS TOTAL DI BAWAH ---
                // ---------------------------------------------------------
                
                // Cari baris terakhir data (misal data habis di baris 20)
                $lastDataRow = $sheet->getHighestRow(); 
                
                // Tentukan baris untuk Total (baris 21)
                $totalRow = $lastDataRow + 1;

                // Tulis Label "Total Pengeluaran" (Merge kolom A sampai E)
                $sheet->mergeCells('A' . $totalRow . ':E' . $totalRow);
                $sheet->setCellValue('A' . $totalRow, 'Total Pengeluaran');

                // Tulis Rumus SUM di Kolom F (Jumlah)
                // Rumus: =SUM(F5:F20)
                $sheet->setCellValue('F' . $totalRow, '=SUM(F5:F' . $lastDataRow . ')');

                // Styling Baris Total (Kuning, Bold)
                $sheet->getStyle('A' . $totalRow . ':G' . $totalRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF99']], // Kuning Muda
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                
                // Align Kanan untuk Label "Total Pengeluaran"
                $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                
                // Format Angka di Cell Total agar ada koma desimal
                $sheet->getStyle('F' . $totalRow)->getNumberFormat()->setFormatCode('#,##0.00');

                // ---------------------------------------------------------
                // --- BORDER UNTUK SELURUH TABEL (TERMASUK TOTAL) ---
                // ---------------------------------------------------------
                
                // Range border dari A5 sampai G + baris Total
                $tableRange = 'A5:G' . $totalRow;

                $sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }

    public function collection()
    {
        $query = CashOutlay::with(['cashAccount', 'details.outlayAccount']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('transaction_date', [$this->startDate, $this->endDate]);
        }

        // Ambil data header
        $headers = $query->orderBy('transaction_date', 'asc')->get();
        
        // FLATTEN DATA: Ubah Header -> List of Details
        $flattened = collect();
        
        foreach ($headers as $header) {
            foreach ($header->details as $detail) {
                // Kita buat objek custom untuk setiap baris detail
                $row = new \stdClass();
                $row->header = $header;
                $row->detail = $detail;
                $flattened->push($row);
            }
        }

        return $flattened;
    }

    // Mapping sesuai kolom di gambar
    public function map($row): array
    {
        $header = $row->header;
        $detail = $row->detail;

        return [
            $this->no++, // No
            $header->outlay_code, // Nomor BKK
            date('d-m-Y', strtotime($header->transaction_date)), // Tgl.BKK
            $header->cashAccount->name ?? 'KAS UMUM', // Akun Kas (K)
            $detail->outlayAccount->name ?? '-', // Akun Biaya (D)
            $detail->amount, // Jumlah
            $detail->notes ?? $header->global_note ?? '-' // Keterangan
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor BKK',
            'Tgl.BKK',
            'Akun Kas (K)',
            'Akun Biaya (D)',
            'Jumlah',
            'Keterangan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => '000000']], // Merah seperti gambar
                'alignment' => ['horizontal' => 'center']
            ],
            // Format Kolom Jumlah (F) menjadi Accounting
            'F' => ['numberFormat' => ['formatCode' => '#,##0.00']],
        ];
    }
}