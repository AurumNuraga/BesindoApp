<?php

namespace App\Exports;

use App\Models\CashInflow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashInflowReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = CashInflow::with(['cashAccount', 'incomeAccount', 'user']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('inflow_date', [$this->startDate, $this->endDate]);
        }

        return $query->latest()->get();
    }

    public function map($inflow): array
    {
        return [
            date('d/m/Y', strtotime($inflow->inflow_date)),
            $inflow->inflow_number,
            $inflow->incomeAccount->name ?? '-', // Kategori Pendapatan
            $inflow->cashAccount->name ?? '-',   // Masuk ke Kas
            $inflow->description,
            $inflow->depositor_name, // Penyetor
            $inflow->amount,
            $inflow->user->name
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No. BKM',
            'Akun Pendapatan',
            'Masuk Ke Kas',
            'Keterangan',
            'Penyetor',
            'Jumlah (Rp)',
            'Admin'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}