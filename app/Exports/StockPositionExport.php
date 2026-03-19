<?php

namespace App\Exports;

use App\Models\Product;
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

class StockPositionExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize, 
    WithStyles,
    WithCustomStartCell,
    WithEvents
{
    protected $date;
    private $no = 1;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function startCell(): string
    {
        return 'A5'; // Header dimulai dari baris 5
    }

    public function collection()
    {
        return Product::orderBy('name')->get();
    }

    public function map($product): array
    {
        // 1. Hitung Stok Real
        $inQty = $product->purchaseDetails()
            ->whereHas('purchaseTransaction', function($q) {
                $q->where('purchase_date', '<=', $this->date);
            })->sum('quantity');

        $outQty = $product->saleDetails()
            ->whereHas('saleTransaction', function($q) {
                $q->where('transaction_date', '<=', $this->date);
            })->sum('quantity');
            
        $stockAtDate = $inQty - $outQty;

        return [
            $this->no++,
            $product->name,
            $stockAtDate,       // Stok Real
            $product->unit ?? 'Pcs',
            0,                  // Stok (Kolom Biru - 0)
            $product->unit ?? 'Pcs',
            0,                  // Stok (Kolom 3 - 0)
            'TOKO'              // <--- UBAH DISINI (Default: TOKO)
        ];
    }

    public function headings(): array
    {
        return [
            'NO', 'NAMA BARANG', 'STOK', 'Sat', 'S-2', 'Sat', 'S-3', 'Gudang'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            5 => [ // Baris Header
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // --- 1. JUDUL LAPORAN ---
                // Kiri: PT BESINDO...
                $sheet->mergeCells('A1:C1'); 
                $sheet->setCellValue('A1', 'PT. BESINDO JAYA ABADI');
                $sheet->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 14]]);
                
                $sheet->mergeCells('A2:C2'); 
                $sheet->setCellValue('A2', 'JL. BETOAMBARI, BAUBAU');

                // Kanan: DAFTAR STOK...
                $sheet->mergeCells('E1:H1'); 
                $sheet->setCellValue('E1', 'DAFTAR STOK BARANG');
                $sheet->getStyle('E1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']], // Hitam
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                
                $sheet->mergeCells('E2:H2');
                $sheet->setCellValue('E2', 'Per Tanggal: ' . date('d-m-Y', strtotime($this->date)));
                $sheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // --- 2. HEADER TABEL (Baris 5) ---
                // Header 'STOK' harus menaungi kolom C s/d G (Sesuai gambar)
                // Tapi karena keterbatasan library export sederhana, kita atur manual teksnya
                
                // Warna Header (Cyan/Biru Muda)
                $sheet->getStyle('A5:H5')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00FFFF']], // Cyan
                ]);
                
                // Override Teks Header agar persis gambar
                $sheet->setCellValue('C5', 'STOK');
                $sheet->mergeCells('C5:G5'); // Merge kolom Stok
                
                // --- 3. BORDER & STRIPING DATA ---
                $lastRow = $sheet->getHighestRow();
                
                $sheet->getStyle('A5:H' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                    ],
                ]);

                // --- 4. WARNAI KOLOM KHUSUS (Sesuai Gambar) ---
                // Kolom E (Stok ke-2) warnanya Biru Gelap di Gambar
                $sheet->getStyle('E6:E' . $lastRow)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F4E78']], // Biru Tua
                    'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Kolom H (Gudang) background Cyan muda
                $sheet->getStyle('H6:H' . $lastRow)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCFFFF']], // Cyan Pucat
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                
                // Kolom No & Nama
                $sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C6:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}