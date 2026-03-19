<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleTransaction;
use App\Models\PurchaseTransaction;
use App\Models\CashOutlay;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Default Tanggal (1 Bulan ke belakang)
        $startDate = $request->start_date ?? now()->subMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');

        // --- STATISTIK BERDASARKAN FILTER TANGGAL ---
        
        // PERBAIKAN: Ganti 'transaction_start_date' menjadi 'transaction_date'
        $totalSales = SaleTransaction::whereBetween('transaction_date', [$startDate, $endDate])
                        ->sum('grand_total');

        // Total Pembelian
        $totalPurchases = PurchaseTransaction::whereBetween('purchase_date', [$startDate, $endDate])
                        ->sum('grand_total');

        // Total Pengeluaran Operasional (Pakai created_at karena biasanya outlay tidak punya tgl khusus)
        // Pastikan format date stringnya benar
        $totalExpenses = CashOutlay::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                        ->sum('total_amount');

        // Hitung Arus Kas Kasar
        $netCashFlow = $totalSales - ($totalPurchases + $totalExpenses);


        // --- STATISTIK GLOBAL ---

        // Total Piutang (Grand Total > Done Payment)
        // Gunakan get() -> sum() di PHP jika logic database kompleks, atau raw query untuk performa
        $totalReceivable = SaleTransaction::whereColumn('grand_total', '>', 'down_payment')
                            ->get()
                            ->sum(function($t) {
                                return $t->grand_total - $t->down_payment;
                            });

        // Total Hutang
        $totalDebt = PurchaseTransaction::whereColumn('grand_total', '>', 'done_payment')
                            ->get()
                            ->sum(function($t) {
                                return $t->grand_total - $t->done_payment;
                            });

        // Produk Stok Menipis (<= 100 sesuai request Anda)
        $lowStockProducts = Product::with('stock')
                            ->whereHas('stock', function($q) {
                                $q->where('stock', '<=', 100);
                            })
                            ->take(5)
                            ->get();

        // 5 Transaksi Penjualan Terakhir
        $recentSales = SaleTransaction::with('customer')
                        ->latest()
                        ->take(5)
                        ->get();

        return view('dashboard', compact(
            'startDate', 'endDate',
            'totalSales', 'totalPurchases', 'totalExpenses', 'netCashFlow',
            'totalReceivable', 'totalDebt',
            'lowStockProducts', 'recentSales'
        ));
    }
}