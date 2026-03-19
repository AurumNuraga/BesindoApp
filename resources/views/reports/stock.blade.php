<x-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Laporan Mutasi Stok</h2>
            <p class="text-gray-600">Riwayat detail barang masuk dan keluar.</p>
        </div>
        
        <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md mb-6 border-l-4 border-purple-500">
        <form action="{{ route('reports.stock') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="" class="w-full border-gray-300 rounded focus:ring-purple-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="" class="w-full border-gray-300 rounded focus:ring-purple-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Filter Produk</label>
                <select name="product_id" class="w-full border-gray-300 rounded focus:ring-purple-500">
                    <option value="">-- Semua Produk --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                            {{ $prod->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded font-bold hover:bg-purple-700 transition w-full">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('reports.stock.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded font-bold hover:bg-green-700 transition w-full text-center flex items-center justify-center">
                    <i class="fas fa-file-excel mr-2"></i> Excel
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-800 text-white uppercase">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3 text-center">Jenis</th>
                        <th class="px-4 py-3">No. Referensi</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3 text-center">Qty</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($mutations as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                                {{ date('d/m/Y H:i', strtotime($row['date'])) }}
                            </td>
                            <td class="px-4 py-3 font-bold text-gray-800">
                                {{ $row['product_name'] }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($row['type'] == 'MASUK')
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded">MASUK</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-1 rounded">KELUAR</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">
                                {{ $row['reference'] }}
                            </td>
                            <td class="px-4 py-3 text-gray-500 italic">
                                {{ $row['description'] }}
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-lg {{ $row['type'] == 'MASUK' ? 'text-blue-600' : 'text-red-600' }}">
                                {{ $row['type'] == 'MASUK' ? '+' : '-' }}{{ $row['qty'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-boxes text-4xl mb-3 text-gray-300"></i><br>
                                Tidak ada mutasi stok pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>