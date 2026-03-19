<x-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Data Master</h2>
        <p class="text-gray-600">Silakan pilih data yang ingin Anda kelola.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="{{ route('products.index') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-box-open text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">DM Barang</h3>
            <p class="text-gray-500 text-sm">Kelola detail barang.</p>
        </a>

        <a href="{{ route('suppliers.index') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-truck text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">DM Supplier</h3>
            <p class="text-gray-500 text-sm">Kelola data supplier.</p>
        </a>
        
        <a href="{{ route('categories.index') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-tags text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">DM Jenis Barang</h3>
            <p class="text-gray-500 text-sm">Kelola jenis dan pengelompokan produk.</p>
        </a>

        <a href="{{ route('customers.index') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">DM Pelanggan</h3>
            <p class="text-gray-500 text-sm">Kelola data pelanggan.</p>
        </a>

        <a href="{{ route('brands.index') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-certificate text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">DM Brand</h3>
            <p class="text-gray-500 text-sm">Kelola merk produk.</p>
        </a>

        <a href="{{ route('packages.index') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">DM Kemasan</h3>
            <p class="text-gray-500 text-sm">Kelola jenis kemasan (Batang, Ikat, Sak).</p>
        </a>

    </div>
</x-layout>