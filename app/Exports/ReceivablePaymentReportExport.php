<?php

namespace App\Exports;

use App\Models\ReceivablePayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReceivablePaymentReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = ReceivablePayment::with(['customer', 'saleTransaction', 'user']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('payment_date', [$this->startDate, $this->endDate]);
        }

        return $query->latest()->get();
    }

    public function map($payment): array
    {
        return [
            date('d/m/Y', strtotime($payment->payment_date)),
            $payment->payment_number,
            $payment->customer->name,
            $payment->saleTransaction->invoice_code ?? '-', // No Faktur Penjualan
            $payment->notes,
            $payment->amount_paid,
            $payment->user->name
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Terima',
            'No. Bukti Terima',
            'Customer',
            'Faktur Penjualan',
            'Keterangan',
            'Jumlah Diterima (Rp)',
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