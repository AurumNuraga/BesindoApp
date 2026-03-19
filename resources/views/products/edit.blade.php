<x-layout>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Barang</h2>

        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">A. Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ $product->name }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                            <option value="Active" {{ $product->status == 'Active' ? 'selected' : '' }}>Aktif</option>
                            <option value="Inactive" {{ $product->status == 'Inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Barang <span class="text-red-500">*</span></label>
                        <select name="category_id" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Supplier <span class="text-red-500">*</span></label>
                        <select name="supplier_id" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ $product->supplier_id == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
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
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Barcode</label>
                        <input type="text" name="barcode" value="{{ $product->barcode }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                  
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Unit per Produk</label>
                        <input type="number" name="unit_per_product" value="{{ $product->unit_per_product }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Unit per Koli</label>
                        <input type="number" name="unit_per_koli" value="{{ $product->unit_per_koli }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                </div>
            </div>

            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">C. Harga & Pajak</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga Modal</label>
                        <input type="number" step="0.01" name="capital_price" value="{{ $product->capital_price }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga Jual</label>
                        <input type="number" step="0.01" name="sell_price" value="{{ $product->sell_price }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pajak (Tax)</label>
                        <input type="text" name="tax" value="{{ $product->tax }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                     <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Ongkir Ekspedisi</label>
                        <input type="number" step="0.01" name="expedition_price" value="{{ $product->expedition_price }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-blue-600 mb-4">D. Fisik & Logistik</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Rak</label>
                        <input type="text" name="location" value="{{ $product->location }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kemasan</label>
                        <select name="package_id" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                            <option value="">-- Pilih Kemasan --</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}" {{ $product->package_id == $pkg->id ? 'selected' : '' }}>
                                    {{ $pkg->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Warna</label>
                        <input type="text" name="color" value="{{ $product->color }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                     <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Berat (kg)</label>
                        <input type="number" step="0.01" name="weight" value="{{ $product->weight }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Panjang (cm)</label>
                        <input type="number" step="0.01" name="length" value="{{ $product->length }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lebar (cm)</label>
                        <input type="number" step="0.01" name="width" value="{{ $product->width }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tinggi (cm)</label>
                        <input type="number" step="0.01" name="height" value="{{ $product->height }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                     <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Volume</label>
                        <input type="number" step="0.01" name="volume" value="{{ $product->volume }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('products.index') }}" class="px-6 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600 transition">Batal</a>
                <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition shadow-lg">Update Barang</button>
            </div>
        </form>
    </div>
</x-layout>