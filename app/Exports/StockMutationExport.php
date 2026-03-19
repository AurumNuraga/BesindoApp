<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockMutationExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($row): array
    {
        return [
            date('d/m/Y H:i', strtotime($row['date'])),
            $row['product_name'],
            $row['type'], // Masuk / Keluar
            $row['reference'], // No Faktur
            $row['qty'],
            $row['description']
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Produk',
            'Jenis Transaksi',
            'No. Referensi',
            'Jumlah (Qty)',
            'Keterangan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}