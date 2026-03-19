<?php

namespace App\Http\Controllers;

use App\Models\ReceivablePayment;
use App\Models\SaleTransaction;
use App\Models\Customer;
use App\Models\CashAccount; // Tambahkan ini
use App\Models\User;        // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReceivablePaymentController extends Controller
{
    public function create()
    {
        $customers = Customer::all();
        $cashAccounts = CashAccount::all(); // Untuk dropdown Kas
        $users = User::all(); // Untuk dropdown Sales & Kolektor
        
        // Generate No Bukti: 801-BKM-... (Sesuai gambar Pelunasan Piutang)
        $today = date('my');
        $last = ReceivablePayment::where('payment_number', 'like', "%BKM-$today%")->latest()->first();
        $nextNo = $last ? intval(substr($last->payment_number, -4)) + 1 : 1;
        $code = "801-BKM-$today" . sprintf("%08d", $nextNo);

        return view('finance.receivable.create', compact('customers', 'cashAccounts', 'users', 'code'));
    }

    public function store(Request $request)
{
    $request->validate([
        'payment_number' => 'required|unique:receivable_payments,payment_number',
        'payment_date' => 'required|date',
        'customer_id' => 'required',
        'cash_account_id' => 'required',
        
        // Validasi Array Detail
        'details' => 'required|array|min:1',
        'details.*.invoice_id' => 'required', // ID Faktur Penjualan
        'details.*.amount' => 'required|numeric|min:1',
    ]);

    try {
        DB::transaction(function () use ($request) {
            
            // 1. Hitung Total Header
            $totalAmount = 0;
            foreach ($request->details as $d) {
                $totalAmount += $d['amount'];
            }

            // 2. Simpan Header
            $payment = ReceivablePayment::create([
                'payment_number' => $request->payment_number,
                'payment_date' => $request->payment_date,
                'customer_id' => $request->customer_id,
                'collector_id' => $request->collector_id,
                'sales_id' => $request->sales_id,
                'cash_account_id' => $request->cash_account_id,
                'is_giro_cek' => $request->has('is_giro_cek'),
                'total_amount' => $totalAmount,
                'global_note' => $request->global_note,
                'user_id' => Auth::id(),
            ]);

            // 3. Simpan Detail & Update Saldo Piutang Faktur
            foreach ($request->details as $item) {
                // Ambil data faktur penjualan
                $sale = SaleTransaction::lockForUpdate()->find($item['invoice_id']);
                
                // Cek sisa piutang (Safety Check)
                $remaining = $sale->grand_total - $sale->down_payment;
                if ($item['amount'] > $remaining) {
                    throw new \Exception("Pembayaran melebihi sisa piutang untuk faktur: " . $sale->invoice_code);
                }

                // Simpan Detail
                $payment->details()->create([
                    'sale_transaction_id' => $item['invoice_id'],
                    'amount_paid' => $item['amount'],
                    'notes' => $item['notes'] ?? null,
                ]);

                // Update Saldo Terbayar di Faktur Penjualan
                $sale->increment('down_payment', $item['amount']);
            }
        });

        return redirect()->route('finance.receivable.create')->with('success', 'Pelunasan Piutang Tersimpan!');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage())->withInput();
    }
}
    // AJAX: Ambil Piutang Customer
    public function getUnpaidSales($customerId)
{
    // Ambil penjualan, load sum retur
    $sales = SaleTransaction::where('customer_id', $customerId)
        ->withSum('saleReturn', 'grand_total') // Hitung total retur penjualan
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function($sale) {
            // Hitung Retur
            $totalRetur = $sale->sale_return_sum_grand_total ?? 0;

            // Hitung Sisa Piutang
            // Sisa = Total Jual - Retur - Sudah Diterima (down_payment)
            $remaining = $sale->grand_total - $totalRetur - $sale->down_payment;

            return [
                'id' => $sale->id,
                'text' => $sale->invoice_code,
                'total' => $sale->grand_total,
                'retur' => $totalRetur,
                'paid' => $sale->down_payment,
                'remaining' => $remaining
            ];
        })
        // Filter hanya yang belum lunas (Sisa > 0)
        ->filter(function($sale) {
            return $sale['remaining'] > 1;
        })
        ->values();

    return response()->json($sales);
}
}