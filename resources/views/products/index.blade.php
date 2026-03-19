<x-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Daftar Barang</h2>
            <p class="text-gray-600">Manajemen data master produk lengkap.</p>
        </div>
        <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            <i class="fas fa-plus mr-2"></i> Tambah Barang
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow w-full overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-center bg-gray-50 sticky left-0 z-10 shadow-sm">Nama Barang</th> 
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Jenis</th>
                        <th class="px-6 py-3 text-center">Supplier</th>
                        <th class="px-6 py-3 text-center">Brand</th>
                        <th class="px-6 py-3 text-center">Barcode</th>
                        <th class="px-6 py-3 text-center">Isi/Unit</th>
                        <th class="px-6 py-3 text-center">Isi/Koli</th>
                        <th class="px-6 py-3 text-center">Harga Modal</th>
                        <th class="px-6 py-3 text-center">Harga Jual</th>
                        <th class="px-6 py-3 text-center">Pajak</th>
                        <th class="px-6 py-3 text-center">Harga Ekspedisi</th>
                        <th class="px-6 py-3 text-center">Lokasi</th>
                        <th class="px-6 py-3 text-center">Kemasan</th> <th class="px-6 py-3 text-center">Warna</th>
                        <th class="px-6 py-3 text-center">Berat</th>
                        <th class="px-6 py-3 text-center">Panjang</th>
                        <th class="px-6 py-3 text-center">Lebar</th>
                        <th class="px-6 py-3 text-center">Tinggi</th>
                        <th class="px-6 py-3 text-center">Volume</th>
                        <th class="px-6 py-3 text-center bg-gray-50 sticky right-0 z-10 shadow-sm">Aksi</th> </tr>
                </thead>
                <tbody>
                    @forelse($products as $item)
                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                        
                        <td class="px-6 py-4 font-bold text-gray-900 bg-white sticky left-0 z-10 shadow-sm border-r">
                            {{ $item->name }}
                        </td>

                        <td class="px-4 py-4">
                            @if($item->status == 'Active')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Aktif</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Non-Aktif</span>
                            @endif
                        </td>

                        <td class="px-4 py-4">{{ $item->category->name ?? '-' }}</td>
                        <td class="px-4 py-4">{{ $item->supplier->name ?? '-' }}</td>
                        
                        <td class="px-4 py-4">{{ $item->brand->name ?? '-' }}</td>
                        
                        <td class="px-4 py-4 font-mono">{{ $item->barcode ?? '-' }}</td>
                        
                        <td class="px-4 py-4">{{ $item->unit_per_product ?? 0 }}</td>
                        <td class="px-4 py-4">{{ $item->unit_per_koli ?? 0 }}</td>

                        <td class="px-4 py-4">Rp {{ number_format($item->capital_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-4 font-bold text-green-600">Rp {{ number_format($item->sell_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-4">{{ $item->tax ?? '-' }}</td>
                        <td class="px-4 py-4">Rp {{ number_format($item->expedition_price, 0, ',', '.') }}</td>

                        <td class="px-4 py-4">{{ $item->location ?? '-' }}</td>
                        
                        <td class="px-4 py-4">{{ $item->package->name ?? '-' }}</td>
                        
                        <td class="px-4 py-4">{{ $item->color ?? '-' }}</td>
                        
                        <td class="px-4 py-4">{{ (float)$item->weight }}</td>
                        <td class="px-4 py-4">{{ (float)$item->length }}</td>
                        <td class="px-4 py-4">{{ (float)$item->width }}</td>
                        <td class="px-4 py-4">{{ (float)$item->height }}</td>
                        <td class="px-4 py-4">{{ (float)$item->volume }}</td>

                        <td class="px-4 py-4 text-center bg-white sticky right-0 z-10 shadow-sm border-l">
                            <div class="flex item-center justify-center space-x-3">
                                <a href="{{ route('products.edit', $item->id) }}" class="text-yellow-500 hover:text-yellow-600" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="23" class="px-6 py-8 text-center text-gray-400">
                            Belum ada data barang. Silakan tambah baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t">
            {{ $products->links() }}
        </div>
    </div>
</x-layout>