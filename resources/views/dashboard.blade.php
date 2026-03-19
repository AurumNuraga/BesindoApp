<x-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
            <p class="text-gray-600">Ringkasan aktivitas bisnis Anda.</p>
        </div>
        
        <form action="{{ route('dashboard') }}" method="GET" class="bg-white p-2 rounded-lg shadow-sm flex flex-col md:flex-row gap-2 border">
            <div class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ $startDate }}" class="border-gray-300 rounded text-sm focus:ring-indigo-500">
                <span class="text-gray-400">-</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="border-gray-300 rounded text-sm focus:ring-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-indigo-700 transition">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-emerald-500 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-shopping-cart text-6xl text-emerald-600"></i>
            </div>
            <div class="text-gray-500 text-sm font-bold uppercase tracking-wider">Total Penjualan</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            <div class="text-xs text-emerald-600 mt-1 font-semibold">Omset Periode Ini</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-boxes text-6xl text-blue-600"></i>
            </div>
            <div class="text-gray-500 text-sm font-bold uppercase tracking-wider">Total Pembelian</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</div>
            <div class="text-xs text-blue-600 mt-1 font-semibold">Stok Masuk Periode Ini</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-rose-500 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-wallet text-6xl text-rose-600"></i>
            </div>
            <div class="text-gray-500 text-sm font-bold uppercase tracking-wider">Pengeluaran Biaya</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
            <div class="text-xs text-rose-600 mt-1 font-semibold">Biaya Operasional</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 {{ $netCashFlow >= 0 ? 'border-indigo-500' : 'border-orange-500' }} relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-chart-line text-6xl {{ $netCashFlow >= 0 ? 'text-indigo-600' : 'text-orange-600' }}"></i>
            </div>
            <div class="text-gray-500 text-sm font-bold uppercase tracking-wider">Estimasi Arus Kas</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">Rp {{ number_format($netCashFlow, 0, ',', '.') }}</div>
            <div class="text-xs {{ $netCashFlow >= 0 ? 'text-indigo-600' : 'text-orange-600' }} mt-1 font-semibold">
                (Jual - Beli - Biaya)
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl shadow-sm border border-green-200 flex justify-between items-center">
            <div>
                <h4 class="text-green-800 font-bold text-lg">Total Piutang (Aktif)</h4>
                <p class="text-green-600 text-sm">Uang yang belum dibayar Customer</p>
                <div class="text-3xl font-bold text-green-700 mt-2">Rp {{ number_format($totalReceivable, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white p-3 rounded-full shadow-sm text-green-500">
                <i class="fas fa-hand-holding-dollar text-3xl"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-50 to-red-100 p-6 rounded-xl shadow-sm border border-red-200 flex justify-between items-center">
            <div>
                <h4 class="text-red-800 font-bold text-lg">Total Hutang (Aktif)</h4>
                <p class="text-red-600 text-sm">Kewajiban bayar ke Supplier</p>
                <div class="text-3xl font-bold text-red-700 mt-2">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white p-3 rounded-full shadow-sm text-red-500">
                <i class="fas fa-file-invoice-dollar text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden lg:col-span-1">
            <div class="bg-orange-50 px-6 py-4 border-b border-orange-100 flex justify-between items-center">
                <h3 class="font-bold text-orange-800"><i class="fas fa-exclamation-triangle mr-2"></i> Stok Menipis</h3>
                <span class="bg-orange-200 text-orange-800 text-xs px-2 py-1 rounded-full"><= 100</span>
            </div>
            <div class="p-0">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3 text-center">Sisa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($lowStockProducts as $prod)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-700">{{ $prod->name }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded font-bold text-xs">
                                    {{ $prod->stock->stock ?? 0 }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-4 py-6 text-center text-gray-400">Stok aman terkendali.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden lg:col-span-2">
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100 flex justify-between items-center">
                <h3 class="font-bold text-indigo-800"><i class="fas fa-history mr-2"></i> Penjualan Terakhir</h3>
                <a href="{{ route('sales.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-6 py-3">Faktur</th>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3 text-right">Total</th>
                            <th class="px-6 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentSales as $sale)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 font-bold text-gray-700">{{ $sale->invoice_code }}</td>
                            <td class="px-6 py-3">{{ $sale->customer->name }}</td>
                            <td class="px-6 py-3 text-gray-500 text-xs">
                                {{ date('d/m', strtotime($sale->transaction_date)) }}
                            </td>
                            <td class="px-6 py-3 text-right font-bold">
                                Rp {{ number_format($sale->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if($sale->grand_total <= $sale->down_payment)
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Lunas</span>
                                @else
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada transaksi penjualan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layout>