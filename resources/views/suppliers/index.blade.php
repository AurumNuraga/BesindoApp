<x-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Data Supplier</h2>
            <p class="text-gray-600">Daftar rekanan dan pemasok barang.</p>
        </div>
        <a href="{{ route('suppliers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            <i class="fas fa-plus mr-2"></i> Tambah Supplier
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3">Nama Supplier</th>
                        <th class="px-6 py-3">Kontak (HP/Telp)</th>
                        <th class="px-6 py-3">Alamat</th>
                        <th class="px-6 py-3">NPWP</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-bold text-gray-900">
                            {{ $supplier->name }}
                        </td>
                        <td class="px-6 py-4">
                            <div><i class="fas fa-mobile-alt w-4 text-gray-400"></i> {{ $supplier->hp ?? '-' }}</div>
                            <div><i class="fas fa-phone w-4 text-gray-400"></i> {{ $supplier->telephone ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 truncate max-w-xs" title="{{ $supplier->address }}">
                            {{ $supplier->address ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-mono">
                            {{ $supplier->npwp ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-yellow-500 hover:text-yellow-600">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Hapus supplier {{ $supplier->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                            Belum ada data supplier.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $suppliers->links() }}
        </div>
    </div>
</x-layout>