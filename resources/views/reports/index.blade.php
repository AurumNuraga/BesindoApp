<x-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Laporan</h2>
        <p class="text-gray-600">Pilih jenis laporan yang ingin Anda lihat atau cetak.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <a href="{{ route('reports.sale') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-file-invoice text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Lap. Penjualan</h3>
            <p class="text-gray-500 text-xs">Rekap transaksi penjualan & detail barang keluar.</p>
        </a>

        <a href="{{ route('reports.purchase') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-cart-arrow-down text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Lap. Pembelian</h3>
            <p class="text-gray-500 text-xs">Rekap pembelian barang & stok masuk dari supplier.</p>
        </a>

        <a href="{{ route('reports.debt_payment') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Lap. Pelunasan Utang</h3>
            <p class="text-gray-500 text-xs">Rekap pembayaran hutang dagang ke supplier.</p>
        </a>

        <a href="{{ route('reports.receivable_payment') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-hand-holding-dollar text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Lap. Pelunasan Piutang</h3>
            <p class="text-gray-500 text-xs">History pembayaran piutang dari customer.</p>
        </a>

        <a href="{{ route('reports.outlay') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Lap. Pengeluaran Kas</h3>
            <p class="text-gray-500 text-xs">Laporan biaya operasional & pengeluaran kas kecil.</p>
        </a>

        <a href="{{ route('reports.cash_inflow') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-hand-holding-dollar text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Lap. Penerimaan Kas</h3>
            <p class="text-gray-500 text-xs">Rekap BKM dan pendapatan lain-lain.</p>
        </a>

        <a href="{{ route('reports.stock_position') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-clipboard-check text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Lap. Stok</h3>
            <p class="text-gray-500 text-xs">Cek jumlah stok akhir pada tanggal tertentu.</p>
        </a>

        <a href="{{ route('reports.stock') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-boxes text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Lap. Mutasi Stok</h3>
            <p class="text-gray-500 text-xs">Mutasi barang masuk & keluar per periode.</p>
        </a>

        <a href="{{ route('reports.general_journal') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-book-open text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Lap. Jurnal Umum</h3>
            <p class="text-gray-500 text-xs">Rekap transaksi memorial debit/kredit.</p>
        </a>

    </div>
</x-layout>