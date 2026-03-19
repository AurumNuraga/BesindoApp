<x-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Keuangan</h2>
        <p class="text-gray-600">Silakan pilih data yang ingin Anda kelola.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="{{ route('finance.expense.create') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-hand-holding-dollar text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">Transaksi Pengeluaran Kas</h3>
            <p class="text-gray-500 text-sm">Biaya operasional & pengeluaran umum.</p>
        </a>

        <a href="{{ route('finance.inflow.index') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-hand-holding-dollar text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Transaksi Penerimaan Kas</h3>
            <p class="text-gray-500 text-xs">Catat pendapatan bunga, modal, dll.</p>
        </a>

        <a href="{{ route('finance.debt.create') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">Transaksi Pelunasan Utang</h3>
            <p class="text-gray-500 text-sm">Bayar hutang dagang ke supplier.</p>
        </a>

        <a href="{{ route('finance.receivable.create') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-cash-register text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1">Transaksi Pelunasan Piutang</h3>
            <p class="text-gray-500 text-sm">Terima pembayaran piutang customer.</p>
        </a>

        <a href="{{ route('journal.index') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-gray-500 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 rounded-full text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-book-open text-xl"></i>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Transaksi Jurnal Umum</h3>
            <p class="text-gray-500 text-xs">Input manual debit/kgrayit & penyesuaian.</p>
        </a>

    </div>
</x-layout>