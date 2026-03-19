<?php

namespace App\Http\Controllers;

use App\Models\DebtPayment;
use App\Models\PurchaseTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DebtPaymentController extends Controller
{
    public function create()
    {
        $suppliers = Supplier::all();
        // Ambil data akun kas
        $cashAccounts = \App\Models\CashAccount::all(); 
        
        // Generate No Bukti: 801-PU-... (Sesuai gambar)
        $today = date('my');
        $last = DebtPayment::where('payment_number', 'like', "%P.U-$today%")->latest()->first();
        $nextNo = $last ? intval(substr($last->payment_number, -8)) + 1 : 1;
        $code = "801-P.U-$today" . sprintf("%08d", $nextNo);

        return view('finance.debt.create', compact('suppliers', 'cashAccounts', 'code'));
    }

    public function store(Request $request)
{
    $request->validate([
        'payment_number' => 'required|unique:debt_payments,payment_number',
        'payment_date' => 'required|date',
        'supplier_id' => 'required',
        'cash_account_id' => 'required',
        
        // Validasi Array Detail
        'details' => 'required|array|min:1',
        'details.*.invoice_id' => 'required', // ID Faktur Pembelian
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
            $payment = DebtPayment::create([
                'payment_number' => $request->payment_number,
                'payment_date' => $request->payment_date,
                'supplier_id' => $request->supplier_id,
                'cash_account_id' => $request->cash_account_id,
                'is_giro_cek' => $request->has('is_giro_cek'),
                'total_amount' => $totalAmount,
                'global_note' => $request->global_note,
                'user_id' => Auth::id(),
            ]);

            // 3. Simpan Detail & Update Saldo Faktur
            foreach ($request->details as $item) {
                $invoice = PurchaseTransaction::lockForUpdate()->find($item['invoice_id']);
                
                // Ambil Total Retur untuk faktur ini
                $totalRetur = \App\Models\PurchaseReturn::where('purchase_transaction_id', $invoice->id)->sum('grand_total');

                // Hitung Sisa Hutang Real-time
                $remaining = $invoice->grand_total - $totalRetur - $invoice->done_payment;

                if ($item['amount'] > $remaining) {
                    throw new \Exception("Pembayaran melebihi sisa hutang (setelah retur) untuk faktur: " . $invoice->purchase_code);
                }

                // Simpan Detail
                $payment->details()->create([
                    'purchase_transaction_id' => $item['invoice_id'],
                    'amount_paid' => $item['amount'],
                    'notes' => $item['notes'] ?? null,
                ]);

                // Update Saldo Terbayar di Faktur
                $invoice->increment('done_payment', $item['amount']);
            }
        });

        return redirect()->route('finance.debt.create')->with('success', 'Pelunasan Tersimpan!');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage())->withInput();
    }
}

    // AJAX: Ambil Faktur yang Belum Lunas
    public function getUnpaidInvoices($supplierId)
{
    $invoices = PurchaseTransaction::where('supplier_id', $supplierId)
        // [OPTIMASI] Hanya ambil data yang 'done_payment' < 'grand_total'
        // Ini membuang transaksi yang sudah lunas murni (tanpa retur) agar query lebih ringan
        ->whereColumn('done_payment', '<', 'grand_total')
        
        // Load jumlah retur
        ->withSum('purchaseReturn', 'grand_total') 
        
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function($inv) {
            // Ambil total retur (handle jika null)
            $totalRetur = $inv->purchase_return_sum_grand_total ?? 0;
            
            // Hitung Sisa Hutang: Total Tagihan - Retur - Sudah Dibayar
            $remaining = $inv->grand_total - $totalRetur - $inv->done_payment;

            return [
                'id' => $inv->id,
                // Format teks untuk dropdown: No Faktur Supplier (Kode Internal)
                'text' => $inv->supplier_invoice_number . ' (' . $inv->purchase_code . ')',
                'total' => $inv->grand_total,
                'retur' => $totalRetur,
                'paid' => $inv->done_payment,
                'remaining' => $remaining
            ];
        })
        // Filter akhir: hanya yang sisa hutangnya masih positif (> 1 perak)
        ->filter(function($inv) {
            return $inv['remaining'] > 1; 
        })
        ->values(); // Reset index array

    return response()->json($invoices);
}
}