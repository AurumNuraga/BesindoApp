<?php

namespace App\Http\Controllers;

use App\Models\PurchaseTransaction;
use App\Models\PurchaseTransactionDetail;
use App\Models\PurchaseCategory;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function create()
    {
        
        $suppliers = Supplier::all(); 
        $products = Product::all();
        $warehouses = Warehouse::all();
        $categories = PurchaseCategory::all();
        
        // Generate No Bukti: BELI/YYYYMM/0001
        $today = date('my');
        $last = PurchaseTransaction::where('purchase_code', 'like', "801-B.T-$today%")->latest()->first();
        $nextNo = $last ? intval(substr($last->purchase_code, -8)) + 1 : 1;
        $code = "801-B.T-$today" . sprintf("%08d", $nextNo);

        return view('purchases.create', compact('suppliers', 'products', 'warehouses', 'categories', 'code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'warehouse_id' => 'required',
            'grand_total_hidden' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Simpan Header
                $purchase = PurchaseTransaction::create([
                    'purchase_code' => $request->purchase_code,
                    'supplier_invoice_number' => $request->supplier_invoice_number,
                    'purchase_order_number' => $request->purchase_order_number,
                    'tax_number' => $request->tax_number,
                    'supplier_fax' => $request->supplier_fax, // Nota Supplier
                    'purchase_category_id' => $request->purchase_category_id,
                    'supplier_id' => $request->supplier_id,
                    'warehouse_id' => $request->warehouse_id,
                    'user_id' => Auth::id(),
                    
                    'purchase_date' => $request->purchase_date,
                    'credit_days' => $request->credit_days ?? 0,
                    'due_date' => $request->due_date,
                    
                    'subtotal' => $request->subtotal_hidden,
                    'tax_rate' => $request->tax_rate ?? 0,
                    'tax_amount' => $request->tax_amount ?? 0,
                    'discount_percent' => $request->discount_percent ?? 0,
                    'discount_amount' => $request->discount_amount ?? 0,
                    'shipping_cost' => $request->shipping_cost ?? 0,
                    'other_expense' => $request->other_expense ?? 0,
                    'done_payment' => $request->done_payment ?? 0,
                    'grand_total' => $request->grand_total_hidden,
                    
                    'notes' => $request->notes,
                    'status' => 'Received'
                ]);

                // 2. Simpan Detail Item
                foreach ($request->products as $item) {
                    if(isset($item['id']) && $item['id']) {
                        
                        $product = Product::find($item['id']);
                        $unit = $product->unit ?? 'Pcs';

                        PurchaseTransactionDetail::create([
                            'purchase_id' => $purchase->id,
                            'product_id' => $item['id'],
                            'unit' => $unit,
                            'quantity' => $item['qty'],
                            'price' => $item['price'],
                            'discount_1' => $item['disc_1'] ?? 0,
                            'discount_2' => $item['disc_2'] ?? 0,
                            'discount_rp' => $item['disc_rp'] ?? 0,
                            'subtotal' => $item['subtotal_row']
                        ]);
                        
                        // 3. Update Stok (Increment)
                        $stock = Stock::where('product_id', $item['id'])->first();
                        if($stock) {
                            $stock->increment('stock', $item['qty']);
                        } else {
                            Stock::create(['product_id' => $item['id'], 'stock' => $item['qty']]);
                        }
                        
                        // Update Harga Modal di Master Produk (Opsional)
                        $product->update(['capital_price' => $item['price']]);
                    }
                }
            });

            return redirect()->route('purchases.create')->with('success', 'Pembelian Berhasil Disimpan!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}