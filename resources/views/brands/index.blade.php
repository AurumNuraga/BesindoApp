<x-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Data Brand</h2>
        <a href="{{ route('brands.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Tambah Brand</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden w-full">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Nama Brand</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $brand->name }}</td>
                    <td class="px-6 py-4 text-center flex justify-center space-x-3">
                        <a href="{{ route('brands.edit', $brand->id) }}" class="text-yellow-500 hover:text-yellow-600"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Hapus brand ini?');">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-600"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="2" class="px-6 py-4 text-center">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $brands->links() }}</div>
    </div>
</x-layout>