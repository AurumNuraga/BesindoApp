<x-layout>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Barang Baru</h2>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">A. Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                            <option value="Active">Aktif</option>
                            <option value="Inactive">Tidak Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Barang <span class="text-red-500">*</span></label>
                        <select name="category_id" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Supplier <span class="text-red-500">*</span></label>
                        <select name="supplier_id" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">B. Detail & Spesifikasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Brand</label>
                        <select name="brand_id" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                            <option value="">-- Pilih Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Barcode</label>
                        <input type="text" name="barcode" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Unit per Produk</label>
                        <input type="number" name="unit_per_product" class="w-full border rounded px-3 py-2 focus:outline-blue-500" placeholder="1">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Unit per Koli</label>
                        <input type="number" name="unit_per_koli" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                </div>
            </div>

            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">C. Harga & Pajak</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga Modal</label>
                        <input type="number" step="0.01" name="capital_price" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga Jual</label>
                        <input type="number" step="0.01" name="sell_price" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pajak</label>
                        <input type="text" name="tax" class="w-full border rounded px-3 py-2 focus:outline-blue-500" placeholder="PPN 11%">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga Ekspedisi</label>
                        <input type="number" step="0.01" name="expedition_price" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">D. Fisik & Logistik</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi</label>
                        <input type="text" name="location" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kemasan</label>
                        <select name="package_id" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                            <option value="">-- Pilih Kemasan --</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Warna</label>
                        <input type="text" name="color" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                     <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Berat</label>
                        <input type="number" step="0.01" name="weight" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Panjang</label>
                        <input type="number" step="0.01" name="length" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lebar</label>
                        <input type="number" step="0.01" name="width" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tinggi</label>
                        <input type="number" step="0.01" name="height" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                     <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Volume</label>
                        <input type="number" step="0.01" name="volume" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('products.index') }}" class="px-6 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600 transition">Batal</a>
                <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition shadow-lg">Simpan Barang</button>
            </div>
        </form>
    </div>
</x-layout>