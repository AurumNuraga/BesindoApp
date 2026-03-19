<?php

namespace App\Http\Controllers;

use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\SaleTransaction;
use App\Models\SaleTransactionDetail;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import Log

class SaleReturnController extends Controller
{
    public function create()
    {
        $customers = Customer::all();
        $warehouses = Warehouse::all();
        
        // Generate No Retur: 801-RJ-YYYYMM-XXXX
        $today = date('my');
        $last = SaleReturn::where('return_number', 'like', "801-RJ-$today%")->latest()->first();
        $nextNo = $last ? intval(substr($last->return_number, -4)) + 1 : 1;
        $code = "801-RJ-$today" . sprintf("%08d", $nextNo); // Hapus titik di R.J agar konsisten

        return view('sales.return.create', compact('customers', 'warehouses', 'code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'return_date' => 'required|date',
            'customer_id' => 'required',
            'sale_transaction_id' => 'required',
            'warehouse_id' => 'required',
            'products' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Simpan Header
                $return = SaleReturn::create([
                    'return_number' => $request->return_number,
                    'return_date' => $request->return_date,
                    'customer_id' => $request->customer_id,
                    'sale_transaction_id' => $request->sale_transaction_id,
                    'warehouse_id' => $request->warehouse_id,
                    'user_id' => Auth::id(),
                    
                    'city' => $request->city,
                    'return_type' => $request->return_type,
                    'item_condition' => $request->item_condition,
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
                    'balance' => $this->clean($request->balance_hidden),
                ]);

                // 2. Simpan Detail
                foreach ($request->products as $item) {
                    $qty = $item['qty'];
                    if($qty > 0) {
                        SaleReturnDetail::create([
                            'sale_return_id' => $return->id,
                            'product_id' => $item['product_id'],
                            'sale_transaction_detail_id' => $item['detail_id'],
                            'unit' => $item['unit'],
                            'price' => $item['price'],
                            'quantity' => $qty,
                            'disc_1' => $item['disc_1'] ?? 0,
                            'disc_2' => $item['disc_2'] ?? 0,
                            'disc_reg' => $item['disc_reg'] ?? 0,
                            'disc_trm' => $item['disc_trm'] ?? 0,
                            'subtotal' => $item['subtotal_row']
                        ]);

                        // --- 3. UPDATE STOK (FIXED MULTI-GUDANG) ---
                        // Cari stok di GUDANG YANG DIPILIH ($request->warehouse_id)
                        $stock = Stock::where('product_id', $item['product_id'])
                                      ->first();
                        
                        if($stock) {
                            $stock->increment('stock', $qty);
                        } else {
                            // Jika belum ada record stok di gudang ini, buat baru
                            Stock::create([
                                'product_id' => $item['product_id'],
                                'warehouse_id' => $request->warehouse_id, // Penting!
                                'stock' => $qty
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('sales.return.create')->with('success', 'Retur Penjualan Berhasil Disimpan!');

        } catch (\Exception $e) {
            Log::error("Error Store Return: " . $e->getMessage()); // Log error ke file
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    // Helper
    private function clean($val) {
        if(!$val) return 0;
        return (float) str_replace('.', '', $val);
    }

    // AJAX: Get Invoices
    public function getInvoices($customerId) {
        try {
            // Pastikan menggunakan 'transaction_date'
            $invoices = SaleTransaction::where('customer_id', $customerId)
                    ->orderBy('id', 'desc')
                    ->doesntHave('saleReturn')
                    ->get(['id', 'invoice_code', 'transaction_date', 'grand_total']); // Tambah grand_total jika perlu info nilai

            return response()->json($invoices);
        } catch (\Exception $e) {
            // Ini akan memunculkan pesan error spesifik di tab Network browser
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getInvoiceDetails($id) {
        try {
            $trx = SaleTransaction::with(['details.product', 'user', 'customer'])->findOrFail($id);
            
            $items = $trx->details->map(function($d) {
                // Ambil stok (Opsional: idealnya ambil stok per gudang jika memungkinkan)
                // Disini kita ambil stok pertama yg ketemu (default behavior)
                $currentStock = $d->product->stock->stock ?? 0;

                return [
                    'detail_id' => $d->id,
                    'product_id' => $d->product_id,
                    'code' => $d->product->code ?? $d->product_id, // Fallback jika code null
                    'name' => $d->product->name,
                    'unit' => $d->unit,
                    'price' => $d->price,
                    'qty_sold' => $d->quantity,
                    'disc_1' => $d->disc_1,
                    'disc_2' => $d->disc_2,
                    'disc_reg' => $d->disc_reg,
                    'stock' => $currentStock 
                ];
            });

            return response()->json([
                'salesman' => $trx->user->name ?? '-',
                'city' => $trx->city ?? ($trx->customer->city ?? '-'), // Ambil kota trx dulu, baru customer
                'address' => $trx->customer->address ?? '-',
                'tax_pct' => $trx->tax ?? 0, 
                'items' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}