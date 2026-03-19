<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseTransaction;
use App\Exports\PurchaseReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SaleTransaction;
use App\Exports\SaleReportExport;
use App\Models\CashOutlay;
use App\Exports\OutlayReportExport;
use App\Models\DebtPayment;
use App\Exports\DebtPaymentReportExport;
use App\Models\ReceivablePayment;
use App\Exports\ReceivablePaymentReportExport;
use App\Models\Product;
use App\Exports\StockMutationExport;
use App\Models\SaleTransactionDetail;
use App\Models\PurchaseTransactionDetail;
use App\Exports\StockPositionExport;
use App\Models\CashInflow;
use App\Exports\CashInflowReportExport;
use App\Models\GeneralJournal;
use App\Exports\GeneralJournalReportExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    // --- 1. HALAMAN LAPORAN PEMBELIAN ---
    public function purchaseReport(Request $request)
    {
        // Gunakan Detail agar rincian barang muncul
        $query = PurchaseTransactionDetail::with(['purchaseTransaction.supplier', 'product']);

        if ($request->start_date && $request->end_date) {
            $query->whereHas('purchaseTransaction', function($q) use ($request) {
                $q->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
            });
        }

        // Sorting: Tanggal ASC, ID ASC
        $purchases = $query->join('purchase_transactions', 'purchase_transaction_details.purchase_id', '=', 'purchase_transactions.id')
                     ->select('purchase_transaction_details.*')
                     ->orderBy('purchase_transactions.purchase_date', 'asc')
                     ->orderBy('purchase_transactions.id', 'asc')
                     ->get();

        return view('reports.purchase', compact('purchases'));
    }

    public function exportPurchase(Request $request)
    {
        return Excel::download(
            new PurchaseReportExport($request->start_date, $request->end_date), 
            'daftar-pembelian.xlsx'
        );
    }

    public function saleReport(Request $request)
{
    // Gunakan SaleTransactionDetail agar bisa tampil per barang
    $query = SaleTransactionDetail::with(['saleTransaction.customer', 'saleTransaction.user', 'product']);

    if ($request->start_date && $request->end_date) {
        $query->whereHas('saleTransaction', function($q) use ($request) {
            $q->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
        });
    }

    // UBAH SORTING:
    // 1. Sort Tanggal (Terbaru)
    // 2. Sort ID Transaksi (PENTING: Agar item dalam 1 faktur nempel jadi satu grup)
    $sales = $query->join('sale_transactions', 'sale_transaction_details.sale_transaction_id', '=', 'sale_transactions.id')
                    ->select('sale_transaction_details.*') // Ambil data detail saja
                    ->orderBy('sale_transactions.transaction_date', 'asc')
                    ->orderBy('sale_transactions.id', 'asc')
                    ->get();

    return view('reports.sale', compact('sales'));
}
    public function exportSale(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        return Excel::download(new SaleReportExport($startDate, $endDate), 'laporan-penjualan.xlsx');
    }

    public function outlayReport(Request $request)
{
    // Load relasi 'details.outlayAccount' (Penting!)
    $query = CashOutlay::with(['cashAccount', 'details.outlayAccount', 'user']);

    if ($request->start_date && $request->end_date) {
        $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
    }

    $outlays = $query->orderBy('transaction_date', 'asc')->get();

    return view('reports.outlay', compact('outlays'));
}

    // --- 6. DOWNLOAD EXCEL PENGELUARAN ---
    public function exportOutlay(Request $request)
    {
        return Excel::download(new OutlayReportExport($request->start_date, $request->end_date), 'laporan-pengeluaran.xlsx');
    }

    public function debtPaymentReport(Request $request)
    {
        // Load cashAccount juga
        $query = DebtPayment::with(['supplier', 'details.purchaseTransaction', 'user', 'cashAccount']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('payment_date', [$request->start_date, $request->end_date]);
        }

        // Urutkan berdasarkan tanggal bayar terbaru
        $payments = $query->orderBy('payment_date', 'asc')->get();

        return view('reports.debt_payment', compact('payments'));
    }

    public function exportDebtPayment(Request $request)
    {
        return Excel::download(
            new DebtPaymentReportExport($request->start_date, $request->end_date), 
            'daftar-pelunasan-utang.xlsx'
        );
    }

    public function receivablePaymentReport(Request $request)
    {
        $query = ReceivablePayment::with(['customer', 'details.saleTransaction', 'user']);

        // Filter Tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('payment_date', [$request->start_date, $request->end_date]);
        }

        $payments = $query->latest()->get();

        return view('reports.receivable_payment', compact('payments'));
    }

    // --- 10. DOWNLOAD EXCEL PELUNASAN PIUTANG ---
    public function exportReceivablePayment(Request $request)
    {
        return Excel::download(
            new ReceivablePaymentReportExport($request->start_date, $request->end_date), 
            'laporan-pelunasan-piutang.xlsx'
        );
    }

    public function stockReport(Request $request)
{
    // 1. Default Tanggal: 1 Bulan Terakhir
    $startDate = $request->start_date ?? now()->subMonth()->format('Y-m-d');
    $endDate = $request->end_date ?? now()->format('Y-m-d');
    $productId = $request->product_id;

    // --- 2. AMBIL DATA PENJUALAN (KELUAR) ---
    $sales = SaleTransactionDetail::with(['saleTransaction', 'product'])
        ->whereHas('saleTransaction', function($q) use ($startDate, $endDate) {
            $q->whereBetween('transaction_date', [$startDate, $endDate]);
        });
    if($productId) $sales->where('product_id', $productId);
    
    $salesData = $sales->get()->map(function($item) {
        return [
            'date' => $item->saleTransaction->created_at,
            'product_name' => $item->product->name,
            'type' => 'KELUAR',
            'reference' => $item->saleTransaction->invoice_code,
            'qty' => $item->quantity,
            'description' => 'Penjualan ke ' . ($item->saleTransaction->customer->name ?? '-')
        ];
    });

    // --- 3. AMBIL DATA PEMBELIAN (MASUK) ---
    $purchases = PurchaseTransactionDetail::with(['purchaseTransaction', 'product'])
        ->whereHas('purchaseTransaction', function($q) use ($startDate, $endDate) {
            $q->whereBetween('purchase_date', [$startDate, $endDate]);
        });
    if($productId) $purchases->where('product_id', $productId);

    $purchasesData = $purchases->get()->map(function($item) {
        return [
            'date' => $item->purchaseTransaction->created_at,
            'product_name' => $item->product->name,
            'type' => 'MASUK',
            'reference' => $item->purchaseTransaction->purchase_code,
            'qty' => $item->quantity,
            'description' => 'Pembelian dari ' . ($item->purchaseTransaction->supplier->name ?? '-')
        ];
    });

    // --- 4. AMBIL DATA RETUR PENJUALAN (MASUK) ---
    // Retur Jual = Barang kembali masuk ke gudang
    $saleReturns = \App\Models\SaleReturnDetail::with(['saleReturn', 'product'])
        ->whereHas('saleReturn', function($q) use ($startDate, $endDate) {
            $q->whereBetween('return_date', [$startDate, $endDate]);
        });
    if($productId) $saleReturns->where('product_id', $productId);

    $saleReturnsData = $saleReturns->get()->map(function($item) {
        return [
            'date' => $item->saleReturn->created_at,
            'product_name' => $item->product->name,
            'type' => 'MASUK',
            'reference' => $item->saleReturn->return_number,
            'qty' => $item->quantity,
            'description' => 'Retur Penjualan (Cust: ' . ($item->saleReturn->customer->name ?? '-') . ')'
        ];
    });

    // --- 5. AMBIL DATA RETUR PEMBELIAN (KELUAR) ---
    // Retur Beli = Barang keluar dikembalikan ke supplier
    $purchaseReturns = \App\Models\PurchaseReturnDetail::with(['purchaseReturn', 'product'])
        ->whereHas('purchaseReturn', function($q) use ($startDate, $endDate) {
            $q->whereBetween('return_date', [$startDate, $endDate]);
        });
    if($productId) $purchaseReturns->where('product_id', $productId);

    $purchaseReturnsData = $purchaseReturns->get()->map(function($item) {
        return [
            'date' => $item->purchaseReturn->created_at,
            'product_name' => $item->product->name,
            'type' => 'KELUAR',
            'reference' => $item->purchaseReturn->return_number,
            'qty' => $item->quantity,
            'description' => 'Retur Pembelian (Supp: ' . ($item->purchaseReturn->supplier->name ?? '-') . ')'
        ];
    });

    // 6. GABUNG & SORTIR SEMUA DATA
    $mutations = $salesData
        ->merge($purchasesData)
        ->merge($saleReturnsData)
        ->merge($purchaseReturnsData)
        ->sortByDesc('date');

    // List produk untuk filter dropdown
    $products = Product::orderBy('name')->get();

    return view('reports.stock', compact('mutations', 'products', 'startDate', 'endDate', 'productId'));
}

    // --- 12. DOWNLOAD EXCEL STOK ---
    public function exportStock(Request $request)
    {
        // ... (Copy logic yg sama persis dengan stockReport di atas utk generate $mutations) ...
        // Agar rapi, idealnya logic query diekstrak ke private function, tapi copy-paste juga oke utk sekarang.
        
        $startDate = $request->start_date ?? now()->subMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        $productId = $request->product_id;

        // ... COPY LOGIC QUERY SALES & PURCHASES DI ATAS KE SINI ...
        // (Saya singkat agar tidak kepanjangan, pastikan Anda copy bagian query Sales, Purchases, dan Merge)
        
        // --- QUERY SALES ---
        $sales = SaleTransactionDetail::with(['saleTransaction', 'product'])
            ->whereHas('saleTransaction', function($q) use ($startDate, $endDate) {
                // UBAH DARI transaction_start_date KE transaction_date
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            });
        if($productId) $sales->where('product_id', $productId);
        
        $salesData = $sales->get()->map(function($item) {
            return [
                'date' => $item->saleTransaction->transaction_date,
                'product_name' => $item->product->name,
                'type' => 'KELUAR',
                'reference' => $item->saleTransaction->invoice_code,
                'qty' => $item->quantity,
                'description' => 'Penjualan ke ' . ($item->saleTransaction->customer->name ?? '-')
            ];
        });

        // --- QUERY PURCHASES ---
        $purchases = PurchaseTransactionDetail::with(['purchaseTransaction', 'product'])
            ->whereHas('purchaseTransaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('purchase_date', [$startDate, $endDate]);
            });
        if($productId) $purchases->where('product_id', $productId);

        $purchasesData = $purchases->get()->map(function($item) {
            return [
                'date' => $item->purchaseTransaction->purchase_date . ' 00:00:00',
                'product_name' => $item->product->name,
                'type' => 'MASUK',
                'reference' => $item->purchaseTransaction->purchase_code,
                'qty' => $item->quantity,
                'description' => 'Pembelian dari ' . ($item->purchaseTransaction->supplier->name ?? '-')
            ];
        });

        $mutations = $salesData->merge($purchasesData)->sortByDesc('date');

        return Excel::download(new StockMutationExport($mutations), 'laporan-mutasi-stok.xlsx');
    }

    public function stockPositionReport(Request $request)
    {
        // Default: Hari ini
        $date = $request->date ?? date('Y-m-d');

        // Ambil Produk & Hitung Stok per Tanggal tersebut
        $products = Product::orderBy('name')->get()->map(function($product) use ($date) {
            
            // 1. HITUNG BARANG MASUK (PEMBELIAN)
            $product->in_qty = $product->purchaseDetails()
                ->whereHas('purchaseTransaction', function($q) use ($date) {
                    $q->where('purchase_date', '<=', $date);
                })
                ->sum('quantity');

            // 2. HITUNG BARANG KELUAR (PENJUALAN)
            $saleQty = $product->saleDetails()
                ->whereHas('saleTransaction', function($q) use ($date) {
                    $q->where('transaction_date', '<=', $date);
                })
                ->sum('quantity');

            // 3. HITUNG BARANG KELUAR (RETUR PEMBELIAN) - INI YANG KURANG TADI
            // Pastikan relasi 'purchaseReturnDetails' sudah Anda tambahkan di Model Product (langkah sebelumnya)
            $returnQty = $product->purchaseReturnDetails()
                ->whereHas('purchaseReturn', function($q) use ($date) {
                    $q->where('return_date', '<=', $date);
                })
                ->sum('quantity');

            $saleReturnQty = $product->saleReturnDetails()
                ->whereHas('saleReturn', function($q) use ($date) {
                    $q->where('return_date', '<=', $date);
                })
                ->sum('quantity');
            
            // TOTAL BARANG KELUAR = PENJUALAN + RETUR KE SUPPLIER
            $product->out_qty = $saleQty + $returnQty - $saleReturnQty;
            
            // Hitung Sisa
            $product->stock_at_date = $product->in_qty - $product->out_qty;
            

            return $product;
        });

        return view('reports.stock_position', compact('products', 'date'));
    }

    // --- 14. DOWNLOAD EXCEL POSISI STOK ---
    public function exportStockPosition(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        return Excel::download(new StockPositionExport($date), 'laporan-posisi-stok-'.$date.'.xlsx');
    }

    public function cashInflowReport(Request $request)
    {
        $query = CashInflow::with(['cashAccount', 'incomeAccount', 'user']);

        // Filter Tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('inflow_date', [$request->start_date, $request->end_date]);
        }

        $inflows = $query->latest()->get();

        return view('reports.cash_inflow', compact('inflows'));
    }

    // --- 16. DOWNLOAD EXCEL PENERIMAAN KAS ---
    public function exportCashInflow(Request $request)
    {
        return Excel::download(
            new CashInflowReportExport($request->start_date, $request->end_date), 
            'laporan-penerimaan-kas.xlsx'
        );
    }

    public function generalJournalReport(Request $request)
{
    // Load relasi 'details' dan akun di dalamnya
    $query = GeneralJournal::with(['details.creditAccount', 'details.debitAccount', 'user']);

    // Filter Tanggal
    if ($request->start_date && $request->end_date) {
        $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
    }

    $journals = $query->latest()->get();

    return view('reports.general_journal', compact('journals'));
}

    // --- 18. DOWNLOAD EXCEL JURNAL UMUM ---
    public function exportGeneralJournal(Request $request)
    {
        return Excel::download(
            new GeneralJournalReportExport($request->start_date, $request->end_date), 
            'laporan-jurnal-umum.xlsx'
        );
    }
}