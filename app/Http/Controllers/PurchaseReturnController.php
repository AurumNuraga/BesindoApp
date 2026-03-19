<?php

namespace App\Http\Controllers;

use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseTransaction;
use App\Models\PurchaseTransactionDetail;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseReturnController extends Controller
{
    public function create()
    {
        $suppliers = Supplier::all();
        $warehouses = Warehouse::all();

        // Generate No Retur: 801-RB-YYYYMM-XXXX
        $today = date('my');
        $last = PurchaseReturn::where('return_number', 'like', "801-R.B-$today%")->latest()->first();
        
        // Fix for substr offset if no previous record exists
        if ($last) {
            $lastNo = intval(substr($last->return_number, -8)); 
            $nextNo = $lastNo + 1;
        } else {
            $nextNo = 1;
        }
        
        $code = "801-R.B-$today" . sprintf("%08d", $nextNo);

        return view('purchases.return.create', compact('suppliers', 'warehouses', 'code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'return_date' => 'required|date',
            'supplier_id' => 'required',
            'purchase_transaction_id' => 'required',
            'warehouse_id' => 'required',
            'products' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Simpan Header
                $return = PurchaseReturn::create([
                    'return_number' => $request->return_number,
                    'return_date' => $request->return_date,
                    'supplier_id' => $request->supplier_id,
                    'purchase_transaction_id' => $request->purchase_transaction_id,
                    'warehouse_id' => $request->warehouse_id,
                    'user_id' => Auth::id(),
                    
                    'city' => $request->city,
                    'return_type' => $request->return_type,
                    'notes' => $request->notes,
                    'ttg_number' => $request->ttg_number,
                    'ttg_date' => $request->ttg_date,
                    
                    'subtotal' => $this->clean($request->subtotal_hidden),
                    'global_discount_pct' => $request->global_discount_pct ?? 0,
                    'global_discount_amount' => $this->clean($request->global_discount_amount),
                    'tax_pct' => $request->tax_pct ?? 0,
                    'tax_amount' => $this->clean($request->tax_amount_hidden),
                    
                    'shipping_cost' => $this->clean($request->shipping_cost),
                    'other_cost' => $this->clean($request->other_cost),
                    
                    'cash_refund' => $this->clean($request->cash_refund),
                    'grand_total' => $this->clean($request->grand_total_hidden),
                    'balance_due' => $this->clean($request->balance_hidden),
                ]);

                // 2. Simpan Detail & Kurangi Stok
                foreach ($request->products as $item) {
                    $qty = $item['qty'];
                    
                    if ($qty > 0) {
                        PurchaseReturnDetail::create([
                            'purchase_return_id' => $return->id,
                            'product_id' => $item['product_id'],
                            'purchase_transaction_detail_id' => $item['detail_id'],
                            'unit' => $item['unit'],
                            'price' => $item['price'],
                            'quantity' => $qty,
                            'disc_1' => $item['disc_1'] ?? 0,
                            'disc_2' => $item['disc_2'] ?? 0,
                            'disc_rp' => $item['disc_rp'] ?? 0,
                            'subtotal' => $item['subtotal_row'],
                            'capital_price' => $item['price'] 
                        ]);

                        // 3. Update Stok (Kurangi karena dikembalikan ke Supplier)
                        $stock = Stock::where('product_id', $item['product_id'])->first();
                        if ($stock) {
                            $stock->decrement('stock', $qty);
                        }
                    }
                }
            });

            return redirect()->route('purchases.return.create')->with('success', 'Retur Pembelian Berhasil Disimpan!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function clean($val) {
        return (float) str_replace('.', '', $val ?? 0);
    }

    // AJAX: Get Invoices
    public function getInvoices($supplierId)
    {
        $invoices = PurchaseTransaction::where('supplier_id', $supplierId)
                    ->whereColumn('grand_total', '>', 'done_payment')
                    ->orderBy('id', 'desc')
                    ->doesntHave('purchaseReturn')
                    ->get(['id', 'supplier_invoice_number', 'purchase_code', 'purchase_date', 'grand_total']);
        
        return response()->json($invoices);
    }

    // AJAX: Get Details (FIXED COLUMN NAME)
    
    public function getDetails($id) {
        // Ambil data via Model Header agar relasi otomatis berjalan
        $trx = PurchaseTransaction::with(['details.product', 'supplier'])->find($id);
        
        if (!$trx) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        // Mapping data
        $items = $trx->details->map(function($d) {
            return [
                'detail_id' => $d->id,
                'product_id' => $d->product_id,
                'code' => $d->product->code ?? $d->product_id,
                'name' => $d->product->name,
                'unit' => $d->unit ?? 'Pcs',
                'price' => $d->price,
                'qty_bought' => $d->quantity, 
                // Cek apakah kolom discount di database pakai disc_1 atau discount_i
                'disc_1' => $d->disc_1 ?? $d->discount_i ?? 0, 
                'disc_2' => $d->disc_2 ?? $d->discount_ii ?? 0,
                'disc_rp' => $d->disc_rp ?? $d->discount_reg ?? 0,
                // Menggunakan withDefault pada model Product mencegah error jika stock null
                'stock' => $d->product->stock->stock ?? 0 
            ];
        });

        return response()->json([
            'address' => $trx->supplier->address ?? '-',
            'city' => $trx->supplier->city ?? '-',
            'tax_pct' => $trx->tax_rate ?? 0,
            'warehouse_id' => $trx->warehouse_id,
            'items' => $items
        ]);
    }
}