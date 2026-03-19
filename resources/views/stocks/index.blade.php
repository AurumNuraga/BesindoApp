<x-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Stok</h2>
        <p class="text-gray-600">Silakan pilih data yang ingin Anda kelola.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="{{ route('stocks.check') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-blue-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 rounded-full text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fas fa-box-open text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">Stok Barang</h3>
            <p class="text-gray-500 text-sm">Kelola stok barang.</p>
        </a>

    </div>
</x-layout>