<x-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Jenis Barang</h2>
            <p class="text-gray-600">Pengelompokan jenis produk.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            <i class="fas fa-plus mr-2"></i> Tambah Jenis
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden w-full">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 w-10">No</th>
                    <th class="px-6 py-3">Nama Jenis</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <th class="px-6 py-3 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $cat)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">
                        {{ $cat->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $cat->description ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex item-center justify-center space-x-2">
                            <a href="{{ route('categories.edit', $cat->id) }}" class="text-yellow-500 hover:text-yellow-600">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
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
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                        Belum ada kategori.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $categories->links() }}
        </div>
    </div>
</x-layout>