<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashInflow;
use App\Models\IncomeAccount;
use App\Models\CashAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CashInflowController extends Controller
{
    public function index()
    {
        $inflows = CashInflow::with(['cashAccount', 'incomeAccount', 'user'])->latest()->paginate(10);
        
        $incomeAccounts = IncomeAccount::all(); 
        $cashAccounts = CashAccount::all();
        $salesUsers = User::all(); 

        // Generate No BKM (BKM-YYYYMM-XXXX)
        $today = date('my');
        $lastTrx = CashInflow::where('inflow_number', 'like', "%BKM-$today%")->orderBy('id', 'desc')->first();
        $nextNo = $lastTrx ? intval(substr($lastTrx->inflow_number, -4)) + 1 : 1;
        $code = "801-BKM-$today" . sprintf("%08d", $nextNo);

        return view('finance.inflow.index', compact('inflows', 'incomeAccounts', 'cashAccounts', 'salesUsers', 'code'));
    }

    public function store(Request $request)
{
    // 1. Validasi
    $request->validate([
        'inflow_number' => 'required|unique:cash_inflows,inflow_number',
        'inflow_date' => 'required|date',
        'cash_account_id' => 'required',
        // Validasi Array Detail
        'details' => 'required|array|min:1',
        'details.*.account_id' => 'required',
        'details.*.amount' => 'required|numeric|min:1',
    ]);

    try {
        DB::transaction(function () use ($request) {
            
            // 2. Hitung Total Header
            $totalAmount = 0;
            foreach ($request->details as $d) {
                $totalAmount += $d['amount'];
            }

            // 3. Simpan Header (CashInflow)
            $inflow = CashInflow::create([
                'inflow_number' => $request->inflow_number,
                'inflow_date' => $request->inflow_date,
                'cash_account_id' => $request->cash_account_id,
                'depositor_name' => $request->depositor_name,
                
                'inflow_type' => $request->inflow_type,
                'sales_id' => $request->sales_id,
                'is_giro_cek' => $request->has('is_giro_cek'),
                
                'total_amount' => $totalAmount,
                'global_note' => $request->global_note,
                'user_id' => Auth::id(),
            ]);

            // 4. Simpan Detail (Looping)
            foreach ($request->details as $item) {
                $inflow->details()->create([
                    'income_account_id' => $item['account_id'],
                    'amount' => $item['amount'],
                    'description' => $item['description'] ?? null,
                ]);
            }
        });

        return back()->with('success', 'BKM Berhasil Disimpan!');

    } catch (\Exception $e) {
        return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
    }
}
}