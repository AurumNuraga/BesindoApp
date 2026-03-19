<?php

namespace App\Http\Controllers;

use App\Models\SaleTransaction;
use App\Models\SaleTransactionDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\Stock; // Tambahkan Model Stock
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $sales = SaleTransaction::with('customer')->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('stock')->get();
        $warehouses = Warehouse::all();
        $salesmen = User::all();
        
        // Generate No Faktur: 801-JLT-YYYYMM-XXXX
        $today = date('my');
        $last = SaleTransaction::where('invoice_code', 'like', "801-JLT-$today%")->latest()->first();
        $nextNo = $last ? intval(substr($last->invoice_code, -4)) + 1 : 1;
        $code = "801-JLT-$today" . sprintf("%08d", $nextNo);

        return view('sales.create', compact('customers', 'products', 'warehouses', 'salesmen', 'code'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'invoice_code' => 'required|unique:sale_transactions,invoice_code',
            'transaction_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'grand_total_hidden' => 'required', // Boleh string dulu, nanti dibersihkan
            'products' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                $subtotal = $this->clean($request->subtotal_hidden);
                $subtotalAfterDisc = $this->clean($request->subtotal_after_disc_hidden);
                $grandTotal = $this->clean($request->grand_total_hidden);
                $downPayment = $this->clean($request->down_payment);
                $transactionType = $request->transaction_type;

                if ($transactionType === 'Cash') {
                    $downPayment = $grandTotal;
                } else {
                    $downPayment = $this->clean($request->down_payment);
                }
                
                // Pastikan angka valid (tidak minus/nol jika wajib)
                if ($grandTotal <= 0) {
                    throw new \Exception("Total transaksi tidak boleh nol.");
                }

                // 3. Simpan Header Transaksi
                $sale = SaleTransaction::create([
                    'invoice_code' => $request->invoice_code,
                    'transaction_type' => $request->transaction_type,
                    'credit_days' => $request->credit_days ?? 0,
                    'transaction_date' => $request->transaction_date,
                    'due_date' => $request->due_date,
                    'manual_invoice_number' => $request->manual_invoice_number,
                    
                    'customer_id' => $request->customer_id,
                    'user_id' => $request->user_id,
                    'warehouse_id' => $request->warehouse_id,
                    
                    // Field Opsional (Rayon/City ambil dari request hidden atau customer)
                    'rayon_code' => $request->rayon_code,
                    'rayon_name' => $request->rayon_name,
                    'city' => $request->city,
                    
                    // Keuangan (Gunakan nilai yang sudah dibersihkan)
                    'subtotal' => $subtotal,
                    'discount_percent' => $request->discount_percent ?? 0,
                    'discount_amount' => $this->clean($request->discount_amount),
                    'subtotal_after_disc' => $subtotalAfterDisc,
                    'shipping_cost' => $this->clean($request->shipping_cost),
                    'other_cost' => $this->clean($request->other_cost),
                    'down_payment' => $downPayment,
                    'grand_total' => $grandTotal,
                    
                    'notes' => $request->notes,
                ]);

                // 4. Simpan Detail & Update Stok
                foreach ($request->products as $item) {
                    // Pastikan item valid (punya ID dan Qty > 0)
                    if(isset($item['id']) && $item['id'] && isset($item['qty']) && $item['qty'] > 0) {
                        
                        // Bersihkan angka detail juga
                        $price = $this->clean($item['price']);
                        $subRow = $this->clean($item['subtotal_row']);
                        $qty = $item['qty'];

                        SaleTransactionDetail::create([
                            'sale_transaction_id' => $sale->id,
                            'product_id' => $item['id'],
                            'unit' => $item['unit'] ?? 'Pcs',
                            'quantity' => $qty,
                            'price' => $price,
                            'disc_1' => $item['disc_1'] ?? 0,
                            'disc_2' => $item['disc_2'] ?? 0,
                            'disc_reg' => $item['disc_reg'] ?? 0, // Pastikan nama field DB benar (disc_reg/disc_rp)
                            'disc_promo' => $item['disc_promo'] ?? 0,
                            'subtotal' => $subRow
                        ]);

                        // --- LOGIKA PENGURANGAN STOK (DECREMENT) ---
                        // Cari stok di gudang yang dipilih
                        $stock = Stock::where('product_id', $item['id'])
                                      ->first();

                        if ($stock) {
                            // Cek apakah stok cukup (Opsional, aktifkan jika perlu)
                            // if ($stock->stock < $qty) throw new \Exception("Stok tidak cukup untuk produk ID: " . $item['id']);
                            
                            $stock->decrement('stock', $qty);
                        } else {
                            // Jika data stok belum ada di gudang ini, buat baru dengan minus (atau throw error)
                            Stock::create([
                                'product_id' => $item['id'],
                                'warehouse_id' => $request->warehouse_id,
                                'stock' => -$qty // Stok jadi minus
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('sales.create')->with('success', 'Penjualan Berhasil Disimpan!');

        } catch (\Exception $e) {
            // Log error untuk developer (bisa dicek di storage/logs/laravel.log)
            \Illuminate\Support\Facades\Log::error('Error Simpan Penjualan: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Fungsi Helper untuk membersihkan format currency (Rp 12.000 -> 12000)
     */
    private function clean($val)
    {
        if (!$val) return 0;

        // 1. Ubah ke string agar aman
        $val = (string) $val;

        // 2. Cek apakah format Indonesia (1.500,00) atau Format Luar (1,500.00)
        
        // Jika ada koma di akhir (misal: 1.500,00), anggap koma sebagai desimal
        if (strpos($val, ',') !== false) {
            // Hapus titik (ribuan)
            $val = str_replace('.', '', $val);
            // Ganti koma jadi titik (desimal sistem)
            $val = str_replace(',', '.', $val);
        } 
        // Jika TIDAK ada koma tapi ada titik (misal: 1.500 atau 1500.00 dari sistem JS)
        else if (strpos($val, '.') !== false) {
            // Cek apakah titik itu ribuan atau desimal?
            // Jika titiknya cuma satu dan di 2/3 digit terakhir, kemungkinan desimal JS (1500.00)
            // Tapi untuk aman di Indonesia: Anggap titik adalah ribuan, KECUALI user kirim format raw.
            
            // SOLUSI PALING AMAN: 
            // Hapus semua titik (anggap ribuan), kecuali Anda yakin frontend kirim format raw 1500.00
            $val = str_replace('.', '', $val);
        }

        // 3. Pastikan tidak ada karakter aneh lain
        $val = preg_replace('/[^0-9.-]/', '', $val);

        return (float) $val;
    }
}