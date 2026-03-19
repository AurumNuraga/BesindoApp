<x-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Laporan Posisi Stok</h2>
            <p class="text-gray-600">Posisi stok barang per tanggal {{ date('d-m-Y', strtotime($date)) }}</p>
        </div>
        
        <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md mb-6 border-l-4 border-cyan-500">
        <form action="{{ route('reports.stock_position') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Tanggal</label>
                <input type="date" name="date" value="{{ $date }}" class="border-gray-300 rounded focus:ring-cyan-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-cyan-600 text-white px-6 py-2 rounded font-bold hover:bg-cyan-700 transition">Filter</button>
                <a href="{{ route('reports.stock_position.export', request()->query()) }}" class="bg-green-600 text-white px-6 py-2 rounded font-bold hover:bg-green-700 transition flex items-center shadow-lg">
                    <i class="fas fa-file-excel mr-2"></i> Download Excel
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse border border-gray-400">
                <thead class="bg-cyan-400 text-black font-bold uppercase border-b-2 border-gray-800">
                    <tr>
                        <th rowspan="2" class="px-2 py-2 border border-black w-10 text-center">NO</th>
                        <th rowspan="2" class="px-2 py-2 border border-black text-center">NAMA BARANG</th>
                        <th colspan="5" class="px-2 py-1 border border-black text-center bg-cyan-400">STOK</th>
                        <th rowspan="2" class="px-2 py-2 border border-black text-center w-24">Gudang</th>
                    </tr>
                    
                </thead>
                <tbody class="divide-y divide-gray-200 font-medium text-gray-900">
                    @php $no = 1; @endphp
                    @forelse($products as $item)
                        <tr class="hover:bg-cyan-50 transition border-b border-gray-400">
                            <td class="px-2 py-1 text-center border-r border-gray-400">{{ $no++ }}</td>
                            
                            <td class="px-2 py-1 border-r border-gray-400">{{ $item->name }}</td>
                            
                            <td class="px-2 py-1 text-center border-r border-gray-400 w-16">{{ number_format($item->stock_at_date, 0, ',', '.') }}</td>
                            <td class="px-2 py-1 text-center border-r border-gray-400 w-16">{{ $item->unit ?? 'Pcs' }}</td>
                            
                            <td class="px-2 py-1 text-center border-r border-gray-400 bg-blue-900 text-white w-16">0</td>
                            <td class="px-2 py-1 text-center border-r border-gray-400 w-16">{{ $item->unit ?? 'Pcs' }}</td>
                            
                            <td class="px-2 py-1 text-center border-r border-gray-400 w-16">0</td>
                            
                            <td class="px-2 py-1 text-center bg-cyan-100 border-l border-gray-400">TOKO</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>