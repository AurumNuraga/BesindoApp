<x-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Cek Stok Gudang</h2>
            <p class="text-gray-600">Monitoring jumlah stok fisik barang saat ini.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow w-full overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 sticky left-0 bg-gray-50">Nama Barang</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Brand</th>
                        <th class="px-6 py-3 text-center">Satuan</th>
                        <th class="px-6 py-3 text-center bg-gray-100 font-bold text-gray-800">SISA STOK</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $item)
                    <tr class="border-b hover:bg-gray-50 transition">
                        
                        <td class="px-6 py-4 font-bold text-gray-900 sticky left-0 ">
                            {{ $item->name }}
                        </td>
                        <td class="px-6 py-4">{{ $item->category->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $item->brand->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">{{ $item->package->name ?? 'Pcs' }}</td>

                        <td class="px-6 py-4 text-center bg-gray-100 border-l border-r">
                            <span class="text-lg font-bold">
                                {{ $item->stock->stock }}
                            </span>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                            Tidak ada data produk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>