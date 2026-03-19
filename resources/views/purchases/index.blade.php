<x-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Pembelian</h2>
        <p class="text-gray-600">Kelola pengadaan barang dan stok masuk.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <a href="{{ route('purchases.create') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-cart-arrow-down text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">Transaksi Pembelian</h3>
            <p class="text-gray-500 text-sm">Kelola transaksi pembelian.</p>
        </a>

        <a href="{{ route('purchases.return.create') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-undo-alt text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">Transaksi Retur Pembelian</h3>
            <p class="text-gray-500 text-sm">Kelola transaksi retur pembelian.</p>
        </a>

    </div>
</x-layout>