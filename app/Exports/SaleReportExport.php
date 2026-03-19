<?php

namespace App\Exports;

use App\Models\SaleTransactionDetail;
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

class SaleReportExport implements 
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
    
    // VARIABLES UNTUK TRACKING
    private $no = 1;
    private $lastInvoiceCode = null;
    
    // Kita mulai tracking baris dari 5 (sesuai startCell)
    private $currentRow = 6; 
    
    // Array untuk menyimpan nomor baris mana saja yang merupakan separator (untuk styling nanti)
    private $separatorRows = []; 

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    /**
     * DI SINI KITA MENYISIPKAN BARIS KOSONG (SEPARATOR)
     */
    public function collection()
    {
        $query = SaleTransactionDetail::with(['saleTransaction.customer', 'saleTransaction.user', 'product']);

        if ($this->startDate && $this->endDate) {
            $query->whereHas('saleTransaction', function($q) {
                $q->whereBetween('transaction_date', [$this->startDate, $this->endDate]);
            });
        }

        // Ambil Data Asli
        $data = $query->join('sale_transactions', 'sale_transaction_details.sale_transaction_id', '=', 'sale_transactions.id')
                     ->select('sale_transaction_details.*')
                     ->orderBy('sale_transactions.transaction_date', 'asc')
                     ->orderBy('sale_transactions.id', 'asc')
                     ->get();

        // Buat Koleksi Baru untuk Output (Data Asli + Separator)
        $output = collect();
        $lastInv = null;

        foreach ($data as $index => $item) {
            $currentInv = $item->saleTransaction->invoice_code;

            // Jika faktur berubah DAN ini bukan baris pertama sama sekali
            // Maka kita sisipkan "Separator"
            if ($lastInv !== null && $currentInv !== $lastInv) {
                $output->push(['is_separator' => true]); 
            }

            $output->push($item);
            $lastInv = $currentInv;
        }

        return $output;
    }

    /**
     * MAPPING DATA KE KOLOM EXCEL
     */
    public function map($row): array
    {
        // 1. CEK APAKAH INI BARIS SEPARATOR?
        if (is_array($row) && isset($row['is_separator'])) {
            // Catat nomor baris ini agar bisa di-style jadi abu-abu nanti
            $this->separatorRows[] = $this->currentRow;
            $this->currentRow++; // Naikkan counter baris excel
            
            // Kembalikan array kosong (Baris Kosong)
            return ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        }

        // 2. JIKA BUKAN, PROSES SEPERTI BIASA
        // Karena $row di sini adalah Object Model (SaleTransactionDetail)
        $detail = $row; 
        $trx = $detail->saleTransaction;
        $product = $detail->product;
        
        $currentInvoiceCode = $trx->invoice_code;
        
        // Cek apakah Faktur Baru (Header harus muncul)
        // Kita bandingkan dengan lastInvoiceCode properti class
        $isNewGroup = ($currentInvoiceCode !== $this->lastInvoiceCode);

        // Jika Grup Baru, isi data Header. Jika sama, kosongkan ('').
        $colNo      = $isNewGroup ? $this->no++ : '';
        $colDate    = $isNewGroup ? date('d-m-Y', strtotime($trx->transaction_date)) : '';
        $colInvoice = $isNewGroup ? $currentInvoiceCode : '';
        $colAS      = $isNewGroup ? '-' : '';
        $colCust    = $isNewGroup ? ($trx->customer->name ?? 'Umum') : '';
        $colSalesId = $isNewGroup ? ($trx->user->id ?? '-') : '';
        $colSalesNm = $isNewGroup ? ($trx->user->name ?? '-') : '';

        // Update Tracker
        $this->lastInvoiceCode = $currentInvoiceCode;
        $this->currentRow++; // Naikkan counter baris excel

        // Hitungan Angka
        $gross = $detail->quantity * $detail->price;
        $disc1_rp = $gross * (($detail->disc_1 ?? 0) / 100);
        $after_d1 = $gross - $disc1_rp;
        $disc2_rp = $after_d1 * (($detail->disc_2 ?? 0) / 100);
        $total_disc_item = $disc1_rp + $disc2_rp + ($detail->disc_rp ?? 0);
        $netto = $detail->subtotal; 

        return [
            $colNo,
            $colDate,
            $colInvoice,
            $colCust,
            $product->code ?? '-',
            $product->name ?? '-',
            $detail->quantity,
            $detail->price,
            $gross,
            $total_disc_item,
            $netto,
            $detail->disc_1 ?? 0,
            $disc1_rp,
            $detail->disc_2 ?? 0,
            $disc2_rp,
            $detail->disc_rp ?? 0,
            $colSalesId,
            $colSalesNm
        ];
    }

    public function headings(): array
    {
        return [
            'No', 'Tanggal', 'Nota', 'Nama Pelanggan', 
            'Kode Barang', 'Nama Barang', 'QTY', 'Harga', 'Jumlah', 
            'Tot. Diskon', 'Netto', 
            '%1', 'Rp Disc 1', '%2', 'Rp Disc 2', 'Pot. Rp', 
            'Kode Sales', 'Nama Sales'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            5 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            'I' => ['numberFormat' => ['formatCode' => '#,##0.00']],
            'J' => ['numberFormat' => ['formatCode' => '#,##0.00']],
            'K' => ['numberFormat' => ['formatCode' => '#,##0.00']],
            'L' => ['numberFormat' => ['formatCode' => '#,##0.00']],
            'N' => ['numberFormat' => ['formatCode' => '#,##0.00']],
            'P' => ['numberFormat' => ['formatCode' => '#,##0.00']],
            'Q' => ['numberFormat' => ['formatCode' => '#,##0.00']],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // JUDUL
                $sheet->mergeCells('A1:R1'); $sheet->setCellValue('A1', 'PT. BESINDO JAYA ABADI');
                $sheet->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 12]]);
                
                $sheet->mergeCells('A2:R2'); $sheet->setCellValue('A2', 'LAPORAN PENJUALAN');
                $sheet->getStyle('A2')->applyFromArray(['font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '008080']]]);
                
                $sheet->mergeCells('A3:R3'); $sheet->setCellValue('A3', "Periode: " . date('d-m-Y', strtotime($this->startDate)) . " s/d " . date('d-m-Y', strtotime($this->endDate)));
                $sheet->getStyle('A3')->applyFromArray(['font' => ['italic' => true]]);

                // BORDER UNTUK SELURUH DATA
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('A5:R' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                    ],
                ]);

                // STYLE KHUSUS UNTUK BARIS SEPARATOR (JEDA)
                // Kita loop array $this->separatorRows yang sudah kita isi di function map()
                foreach ($this->separatorRows as $rowNum) {
                    $sheet->getStyle('A' . $rowNum . ':R' . $rowNum)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'D3D3D3'] // Warna Abu-abu (Gray)
                        ],
                        'borders' => [
                            // Opsional: Hilangkan border vertikal di baris jeda agar terlihat bersih
                            'vertical' => ['borderStyle' => Border::BORDER_NONE],
                        ]
                    ]);
                }
            },
        ];
    }
}