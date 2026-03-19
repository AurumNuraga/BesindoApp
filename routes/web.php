<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BrandCategoryController;
use App\Http\Controllers\PackageCategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DebtPaymentController;
use App\Http\Controllers\ReceivablePaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CashInflowController;
use App\Http\Controllers\JournalController;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

    Route::get('/data-master', function () {
        return view('master.index');
    })->name('master.index');

    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('categories', ProductCategoryController::class);
    Route::resource('customers', CustomerController::class);

    Route::get('/penjualan', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/penjualan/baru', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/penjualan/simpan', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/penjualan/retur/baru', [SaleReturnController::class, 'create'])->name('sales.return.create');
    Route::post('/penjualan/retur/simpan', [SaleReturnController::class, 'store'])->name('sales.return.store');
    Route::get('/penjualan/retur/get-invoices/{customerId}', [SaleReturnController::class, 'getInvoices'])->name('sales.return.getInvoices');
    Route::get('/penjualan/retur/get-invoice-details/{invoiceId}', [SaleReturnController::class, 'getInvoiceDetails'])->name('sales.return.getInvoiceDetails');

    Route::get('/pembelian', function () {
        return view('purchases.index');
    })->name('purchases.index');

    Route::resource('purchases', PurchaseController::class);
    Route::get('/pembelian/retur', [PurchaseReturnController::class, 'create'])->name('purchases.return.create');
    Route::post('/pembelian/retur', [PurchaseReturnController::class, 'store'])->name('purchases.return.store');
    Route::get('/pembelian/retur/get-invoices/{supplierId}', [PurchaseReturnController::class, 'getInvoices']);
    Route::get('/pembelian/retur/get-details/{purchaseId}', [PurchaseReturnController::class, 'getDetails']);
    
    Route::resource('brands', BrandCategoryController::class);
    Route::resource('packages', PackageCategoryController::class);

    Route::get('/stok', [StockController::class, 'index'])->name('stocks.index');

    Route::get('/stok/check', [StockController::class, 'check'])->name('stocks.check');

    Route::get('/keuangan', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/keuangan/pengeluaran', [FinanceController::class, 'createExpense'])->name('finance.expense.create');
    Route::post('/keuangan/pengeluaran', [FinanceController::class, 'storeExpense'])->name('finance.expense.store');
    Route::get('/penerimaan', [CashInflowController::class, 'index'])->name('finance.inflow.index');
    Route::post('/penerimaan', [CashInflowController::class, 'store'])->name('finance.inflow.store');

    Route::get('/keuangan/utang/bayar', [DebtPaymentController::class, 'create'])->name('finance.debt.create');
    Route::post('/keuangan/utang/bayar', [DebtPaymentController::class, 'store'])->name('finance.debt.store');
    Route::get('/keuangan/utang/get-invoices/{supplierId}', [DebtPaymentController::class, 'getUnpaidInvoices']);

    Route::get('/keuangan/piutang/bayar', [ReceivablePaymentController::class, 'create'])->name('finance.receivable.create');
    Route::post('/keuangan/piutang/bayar', [ReceivablePaymentController::class, 'store'])->name('finance.receivable.store');
    
    Route::get('/keuangan/piutang/get-sales/{customerId}', [ReceivablePaymentController::class, 'getUnpaidSales']);

    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/pembelian', [ReportController::class, 'purchaseReport'])->name('reports.purchase');
    Route::get('/laporan/pembelian/export', [ReportController::class, 'exportPurchase'])->name('reports.purchase.export');
    Route::get('/laporan/penjualan', [ReportController::class, 'saleReport'])->name('reports.sale');
    Route::get('/laporan/penjualan/export', [ReportController::class, 'exportSale'])->name('reports.sale.export');
    Route::get('/laporan/pengeluaran', [ReportController::class, 'outlayReport'])->name('reports.outlay');
    Route::get('/laporan/pengeluaran/export', [ReportController::class, 'exportOutlay'])->name('reports.outlay.export');
    Route::get('/laporan/pelunasan-utang', [ReportController::class, 'debtPaymentReport'])->name('reports.debt_payment');
    Route::get('/laporan/pelunasan-utang/export', [ReportController::class, 'exportDebtPayment'])->name('reports.debt_payment.export');
    Route::get('/laporan/pelunasan-piutang', [ReportController::class, 'receivablePaymentReport'])->name('reports.receivable_payment');
    Route::get('/laporan/pelunasan-piutang/export', [ReportController::class, 'exportReceivablePayment'])->name('reports.receivable_payment.export');
    Route::get('/laporan/stok', [ReportController::class, 'stockReport'])->name('reports.stock');
    Route::get('/laporan/stok/export', [ReportController::class, 'exportStock'])->name('reports.stock.export');
    Route::get('/laporan/posisi-stok', [ReportController::class, 'stockPositionReport'])->name('reports.stock_position');
    Route::get('/laporan/posisi-stok/export', [ReportController::class, 'exportStockPosition'])->name('reports.stock_position.export');
    Route::get('/laporan/penerimaan-kas', [ReportController::class, 'cashInflowReport'])->name('reports.cash_inflow');
    Route::get('/laporan/penerimaan-kas/export', [ReportController::class, 'exportCashInflow'])->name('reports.cash_inflow.export');
    Route::get('/laporan/jurnal-umum', [ReportController::class, 'generalJournalReport'])->name('reports.general_journal');
    Route::get('/laporan/jurnal-umum/export', [ReportController::class, 'exportGeneralJournal'])->name('reports.general_journal.export');

    Route::get('/jurnal', [JournalController::class, 'index'])->name('journal.index');
    Route::post('/jurnal', [JournalController::class, 'store'])->name('journal.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});