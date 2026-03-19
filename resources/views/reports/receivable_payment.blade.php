<x-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Laporan Pelunasan Piutang</h2>
            <p class="text-gray-600">Rekap riwayat penerimaan pembayaran dari customer.</p>
        </div>
        
        <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md mb-6 border-l-4 border-emerald-500">
        <form action="{{ route('reports.receivable_payment') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            
            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border-gray-300 rounded focus:ring-emerald-500">
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border-gray-300 rounded focus:ring-emerald-500">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded font-bold hover:bg-emerald-700 transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                
                <a href="{{ route('reports.receivable_payment') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">
                    Reset
                </a>
            </div>

            <div class="ml-auto">
                <a href="{{ route('reports.receivable_payment.export', request()->query()) }}" class="bg-green-600 text-white px-5 py-2 rounded font-bold hover:bg-green-700 transition flex items-center shadow-lg transform hover:-translate-y-1">
                    <i class="fas fa-file-excel mr-2"></i> Download Excel
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-800 text-white uppercase">
                    <tr>
                        <th class="px-4 py-3">Tanggal Terima</th>
                        <th class="px-4 py-3">No. Bukti</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Faktur Penjualan</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Admin</th>
                        <th class="px-4 py-3 text-right">Jumlah Diterima</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payments as $header)
                        @foreach($header->details as $detail)
                        <tr class="hover:bg-emerald-50 transition">
                            <td class="px-4 py-3 whitespace-nowrap">{{ date('d/m/Y', strtotime($header->payment_date)) }}</td>
                            <td class="px-4 py-3 font-bold text-gray-800">{{ $header->payment_number }}</td>
                            <td class="px-4 py-3">{{ $header->customer->name }}</td>
                            
                            <td class="px-4 py-3">
                                @if($detail->saleTransaction)
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-bold">
                                        {{ $detail->saleTransaction->invoice_code }}
                                    </span>
                                @else
                                    <span class="text-red-500 italic">Faktur dihapus</span>
                                @endif
                            </td>
                            
                            <td class="px-4 py-3 text-gray-600 italic">
                                "{{ $header->global_note ?? '-' }}"
                                @if($detail->notes) <br><small class="text-xs">({{ $detail->notes }})</small> @endif
                            </td>

                            <td class="px-4 py-3 text-xs font-bold text-gray-500">
                                {{ $header->user->name ?? 'System' }}
                            </td>
                            
                            <td class="px-4 py-3 text-right font-bold text-emerald-600">
                                Rp {{ number_format($detail->amount_paid, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-search text-4xl mb-3 text-gray-300"></i><br>
                                Tidak ada data pelunasan piutang pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($payments->count() > 0)
                <tfoot class="bg-gray-100 font-bold text-gray-700">
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-right uppercase">Total Penerimaan Piutang:</td>
                        <td class="px-4 py-3 text-right text-emerald-600 text-lg">Rp {{ number_format($payments->sum('total_amount'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-layout>