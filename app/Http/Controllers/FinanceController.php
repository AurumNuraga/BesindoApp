<?php

namespace App\Http\Controllers;

use App\Models\CashOutlay;
use App\Models\CashAccount;
use App\Models\OutlayAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class FinanceController extends Controller
{
    public function index()
    {
        return view('finance.index');
    }

    public function createExpense()
    {
        $cashAccounts = CashAccount::all(); 
        $outlayAccounts = OutlayAccount::all();
        $salesUsers = \App\Models\User::all(); 

        // Generate Kode
        $today = date('Ym');
        $lastTrx = CashOutlay::where('outlay_code', 'like', "%BKK-$today%")->orderBy('id', 'desc')->first();
        $nextNo = $lastTrx ? intval(substr($lastTrx->outlay_code, -8)) + 1 : 1;
        $code = "801-BKK-$today" . sprintf("%08d", $nextNo);

        // --- TAMBAHKAN INI (Ambil Data Riwayat) ---
        $outlays = CashOutlay::with(['cashAccount', 'outlayAccount', 'user'])
                    ->latest()
                    ->paginate(10); // Paginasi 10 per halaman

        return view('finance.outlay.create', compact('cashAccounts', 'outlayAccounts', 'salesUsers', 'code', 'outlays'));
    }

    public function storeExpense(Request $request)
{
    // Validasi Array Detail
    $request->validate([
        'outlay_code' => 'required|unique:cash_outlays,outlay_code',
        'transaction_date' => 'required|date',
        'cash_account_id' => 'required',
        // Validasi detail items
        'details' => 'required|array',
        'details.*.account_id' => 'required',
        'details.*.amount' => 'required|numeric|min:1',
    ]);

    try {
        DB::transaction(function () use ($request) {
            // 1. Hitung Total Header
            $totalAmount = 0;
            foreach ($request->details as $d) {
                $totalAmount += $this->cleanNumber($d['amount']);
            }

            // 2. Simpan Header
            $outlay = CashOutlay::create([
                'outlay_code' => $request->outlay_code,
                'transaction_date' => $request->transaction_date,
                'cash_account_id' => $request->cash_account_id,
                'receiver' => $request->receiver,
                'outlay_type' => $request->outlay_type,
                'sales_id' => $request->sales_id,
                'is_giro_cek' => $request->has('is_giro_cek'),
                'total_amount' => $totalAmount,
                'global_note' => $request->notes, // Catatan umum
                'user_id' => Auth::id(),
            ]);

            // 3. Simpan Detail (Looping)
            foreach ($request->details as $item) {
                $outlay->details()->create([
                    'outlay_account_id' => $item['account_id'],
                    'amount' => $this->cleanNumber($item['amount']),
                    'notes' => $item['notes'] ?? null,
                ]);
            }
        });

        return redirect()->route('finance.expense.create')
            ->with('success', 'BKK Berhasil Disimpan!');

    } catch (\Exception $e) {
        return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
    }
}

// Helper untuk membersihkan format Rupiah (misal: 1.000.000 -> 1000000)
private function cleanNumber($val) {
    return str_replace(['.', ','], '', $val);
}
}