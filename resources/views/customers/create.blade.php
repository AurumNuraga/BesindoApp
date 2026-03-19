<x-layout>
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Pelanggan Baru</h2>

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">A. Informasi Utama</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pelanggan / PT <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">B. Kontak</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">No. HP (Utama)</label>
                        <input type="text" name="hp" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">No. HP (Alternatif)</label>
                        <input type="text" name="hp2" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Telepon Kantor</label>
                        <input type="text" name="telephone" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Fax</label>
                        <input type="text" name="fax" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                </div>
            </div>

            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">C. Alamat Lengkap</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Jalan</label>
                        <textarea name="address" rows="2" class="w-full border rounded px-3 py-2 focus:outline-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kota</label>
                        <input type="text" name="city" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Provinsi</label>
                        <input type="text" name="province" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kode Pos</label>
                        <input type="text" name="postal_code" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Negara</label>
                        <input type="text" name="country" value="Indonesia" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">D. Data Legal & Lainnya</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama di Faktur Pajak</label>
                        <input type="text" name="tax_name" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">NPWP</label>
                        <input type="text" name="npw" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">NPPKP</label>
                        <input type="text" name="nppkp" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Ekspedisi Pilihan</label>
                        <input type="text" name="ekspedisi" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Rekening / Info Bank</label>
                        <input type="text" name="account_number" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                     <div class="md:col-span-3">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan Tambahan</label>
                        <textarea name="information" rows="2" class="w-full border rounded px-3 py-2 focus:outline-blue-500"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('customers.index') }}" class="px-6 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600 transition">Batal</a>
                <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition shadow-lg">Simpan Pelanggan</button>
            </div>
        </form>
    </div>
</x-layout>