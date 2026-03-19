<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JournalAccount;
use App\Models\GeneralJournal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index()
    {
        $accounts = JournalAccount::orderBy('code')->get();
        
        $journals = GeneralJournal::with(['details.creditAccount', 'details.debitAccount']) // Load relasi details
                    ->latest()
                    ->paginate(10);

        $today = date('my');
        $lastTrx = GeneralJournal::where('voucher_no', 'like', "%PEB-$today%")->orderBy('id', 'desc')->first();
        $nextNo = $lastTrx ? intval(substr($lastTrx->voucher_no, -4)) + 1 : 1;
        $code = "801-PEB-$today" . sprintf("%08d", $nextNo);

        return view('finance.journal.index', compact('accounts', 'journals', 'code'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'voucher_no' => 'required|unique:general_journals,voucher_no',
            'transaction_date' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.credit_account_id' => 'required',
            'details.*.debit_account_id' => 'required|different:details.*.credit_account_id', // Debit & Kredit tidak boleh akun sama
            'details.*.amount' => 'required', // Hapus 'numeric' disini agar string "1.000.000" lolos dulu
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // 2. Hitung Total & Bersihkan Angka
                $totalAmount = 0;
                $cleanDetails = [];

                foreach ($request->details as $d) {
                    // Hapus titik ribuan agar jadi angka murni
                    $cleanVal = (float) str_replace(['.', ','], '', $d['amount']);
                    
                    if($cleanVal <= 0) continue; // Skip jika 0

                    $totalAmount += $cleanVal;
                    $cleanDetails[] = [
                        'credit_account_id' => $d['credit_account_id'],
                        'debit_account_id' => $d['debit_account_id'],
                        'amount' => $cleanVal,
                        'memo' => $d['memo'] ?? null,
                    ];
                }

                // 3. Simpan Header
                $journal = GeneralJournal::create([
                    'voucher_no' => $request->voucher_no,
                    'transaction_date' => $request->transaction_date,
                    'total_amount' => $totalAmount,
                    'description' => $request->description,
                    'user_id' => Auth::id(),
                ]);

                // 4. Simpan Detail
                foreach ($cleanDetails as $item) {
                    $journal->details()->create($item);
                }
            });

            return redirect()->route('journal.index')->with('success', 'Jurnal Berhasil Disimpan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }
}