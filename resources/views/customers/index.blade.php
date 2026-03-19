<x-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Data Pelanggan</h2>
            <p class="text-gray-600">Manajemen data customer dan rekanan.</p>
        </div>
        <a href="{{ route('customers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            <i class="fas fa-plus mr-2"></i> Tambah Pelanggan
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
                        <th class="px-6 py-3 bg-gray-50 sticky left-0 z-10 shadow-sm">Nama Pelanggan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Kota/Provinsi</th>
                        <th class="px-4 py-3">No. HP / WA</th>
                        <th class="px-4 py-3">Telepon Kantor</th>
                        <th class="px-4 py-3">Alamat Lengkap</th>
                        <th class="px-4 py-3">Nama Faktur</th>
                        <th class="px-4 py-3">NPWP</th>
                        <th class="px-4 py-3">Ekspedisi</th>
                        <th class="px-4 py-3">Info Tambahan</th>
                        <th class="px-4 py-3 text-center bg-gray-50 sticky right-0 z-10 shadow-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-900 bg-white sticky left-0 z-10 shadow-sm border-r">
                            {{ $customer->name }}
                        </td>
                        <td class="px-4 py-4">
                            @if($customer->status == 'Active')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            {{ $customer->city ?? '-' }}, {{ $customer->province ?? '' }}
                        </td>
                        <td class="px-4 py-4">{{ $customer->hp ?? '-' }}</td>
                        <td class="px-4 py-4">{{ $customer->telephone ?? '-' }}</td>
                        <td class="px-4 py-4 max-w-xs truncate" title="{{ $customer->address }}">{{ $customer->address ?? '-' }}</td>
                        <td class="px-4 py-4">{{ $customer->tax_name ?? '-' }}</td>
                        <td class="px-4 py-4 font-mono">{{ $customer->npw ?? '-' }}</td>
                        <td class="px-4 py-4">{{ $customer->ekspedisi ?? '-' }}</td>
                        <td class="px-4 py-4">{{ $customer->information ?? '-' }}</td>
                        
                        <td class="px-4 py-4 text-center bg-white sticky right-0 z-10 shadow-sm border-l">
                            <div class="flex item-center justify-center space-x-3">
                                <a href="{{ route('customers.edit', $customer->id) }}" class="text-yellow-500 hover:text-yellow-600">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="11" class="px-6 py-8 text-center text-gray-400">Belum ada data pelanggan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $customers->links() }}</div>
    </div>
</x-layout>