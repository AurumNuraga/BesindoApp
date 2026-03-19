<?php

namespace App\Exports;

use App\Models\PurchaseTransactionDetail;
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

class PurchaseReportExport implements 
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
    
    // VARIABLES TRACKING
    private $no = 1;
    private $currentRow = 6; // Data mulai baris 6 (Header di 5)
    private $totalNetto = 0; // Untuk baris total bawah

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
        // AMBIL DATA DETAIL
        $query = PurchaseTransactionDetail::with(['purchaseTransaction.supplier', 'product']);

        if ($this->startDate && $this->endDate) {
            $query->whereHas('purchaseTransaction', function($q) {
                $q->whereBetween('purchase_date', [$this->startDate, $this->endDate]);
            });
        }

        // Urutkan berdasarkan Tanggal & ID Header
        return $query->join('purchase_transactions', 'purchase_transaction_details.purchase_id', '=', 'purchase_transactions.id')
                     ->select('purchase_transaction_details.*')
                     ->orderBy('purchase_transactions.purchase_date', 'asc')
                     ->orderBy('purchase_transactions.id', 'asc')
                     ->get();
    }

    public function map($detail): array
    {
        $trx = $detail->purchaseTransaction;
        $product = $detail->product;

        // Hitung Diskon dan Subtotal
        $gross = $detail->quantity * $detail->price;
        // Asumsi disc_1 adalah persen
        $disc_val = $gross * (($detail->disc_1 ?? 0) / 100); 
        $disc_rp = $detail->disc_rp ?? 0;
        
        // Subtotal di gambar sepertinya Gross - Diskon
        $subtotal = $gross - $disc_val - $disc_rp;
        $this->totalNetto += $subtotal;

        $this->currentRow++;

        return [
            $this->no++, // No
            $trx->supplier_invoice_number, // Nota Supplier
            date('d-m-Y', strtotime($trx->purchase_date)), // Tanggal
            $trx->supplier->name ?? 'Umum', // SUPPLIER
            $product->name ?? '-', // Nama Barang
            $detail->quantity, // Qty
            $detail->unit ?? 'Pcs', // Stn
            $detail->price, // Harga/@
            $detail->disc_1 ?? 0, // Disc %
            $disc_rp, // Disc Rp
            $subtotal, // Subtotal
            0, // %Global (Placeholder/Sesuaikan jika ada)
            $subtotal, // Netto (Sama dgn subtotal baris)
            $trx->purchase_code, // Faktur (Internal Code)
            $trx->tax_number ?? '-' // Faktur Pajak
        ];
    }

    public function headings(): array
    {
        return [
            'No', 'Nota Supplier', 'Tanggal', 'SUPPLIER', 'Nama Barang', 
            'Qty', 'Stn', 'Harga/@', 'Disc %', 'Disc Rp', 'Subtotal', 
            '%Global', 'Netto', 'Faktur', 'Faktur Pajak'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            5 => [ // Header Biru Muda (Cyan)
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00FFFF']], 
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            // Format Angka (Kolom Harga s/d Netto)
            'H' => ['numberFormat' => ['formatCode' => '#,##0.00']], // Harga
            'J' => ['numberFormat' => ['formatCode' => '#,##0.00']], // Disc Rp
            'K' => ['numberFormat' => ['formatCode' => '#,##0.00']], // Subtotal
            'M' => ['numberFormat' => ['formatCode' => '#,##0.00']], // Netto
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // JUDUL (Baris 1) - DAFTAR PEMBELIAN
                $sheet->mergeCells('A1:C1'); 
                $sheet->setCellValue('A1', 'DAFTAR PEMBELIAN');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'underline' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);

                // BORDER TABEL
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('A5:O' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                    ],
                ]);

                // BARIS TOTAL DI BAWAH
                $totalRow = $highestRow + 1;
                
                // Merge kolom subtotal total
                $sheet->setCellValue('K' . $totalRow, $this->totalNetto); // Total Subtotal
                $sheet->setCellValue('M' . $totalRow, $this->totalNetto); // Total Netto

                // Style Total (Format Angka)
                $sheet->getStyle('K' . $totalRow)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('M' . $totalRow)->getNumberFormat()->setFormatCode('#,##0.00');
                
                // Border baris total
                $sheet->getStyle('A' . $totalRow . ':O' . $totalRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                    'font' => ['bold' => true]
                ]);
            },
        ];
    }
}