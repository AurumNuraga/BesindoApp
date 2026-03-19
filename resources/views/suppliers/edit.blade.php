<x-layout>
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Supplier</h2>

        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Supplier / PT <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ $supplier->name }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="w-full border rounded px-3 py-2 focus:outline-blue-500">{{ $supplier->address }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor HP / WhatsApp</label>
                        <input type="text" name="hp" value="{{ $supplier->hp }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Telepon Kantor</label>
                        <input type="text" name="telephone" value="{{ $supplier->telephone }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nomor NPWP</label>
                    <input type="text" name="npwp" value="{{ $supplier->npwp }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <a href="{{ route('suppliers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Supplier</button>
            </div>
        </form>
    </div>
</x-layout>