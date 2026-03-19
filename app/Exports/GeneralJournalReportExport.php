<?php

namespace App\Exports;

use App\Models\GeneralJournal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralJournalReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = GeneralJournal::with(['creditAccount', 'debitAccount', 'user']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('transaction_date', [$this->startDate, $this->endDate]);
        }

        return $query->latest()->get();
    }

    public function map($journal): array
    {
        return [
            date('d/m/Y', strtotime($journal->transaction_date)),
            $journal->voucher_no,
            $journal->creditAccount->code . ' - ' . $journal->creditAccount->name, // Sumber (K)
            $journal->debitAccount->code . ' - ' . $journal->debitAccount->name,   // Tujuan (D)
            $journal->description,
            $journal->amount,
            $journal->user->name
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No. Bukti',
            'Akun Kredit (Sumber)',
            'Akun Debit (Tujuan)',
            'Keterangan',
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