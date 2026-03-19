<x-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Laporan Jurnal Umum</h2>
            <p class="text-gray-600">Rekap transaksi memorial / penyesuaian (General Journal).</p>
        </div>
        
        <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md mb-6 border-l-4 border-slate-500">
        <form action="{{ route('reports.general_journal') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            
            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border-gray-300 rounded focus:ring-slate-500">
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border-gray-300 rounded focus:ring-slate-500">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded font-bold hover:bg-slate-800 transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                
                <a href="{{ route('reports.general_journal') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">
                    Reset
                </a>
            </div>

            <div class="ml-auto">
                <a href="{{ route('reports.general_journal.export', request()->query()) }}" class="bg-green-600 text-white px-5 py-2 rounded font-bold hover:bg-green-700 transition flex items-center shadow-lg transform hover:-translate-y-1">
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
                        <th class="px-6 py-3 font-medium">Tanggal</th>
                        <th class="px-6 py-3 font-medium">No. Bukti</th>
                        <th class="px-6 py-3 font-medium">Akun Kredit </th>
                        <th class="px-6 py-3 font-medium">Akun Debit </th>
                        <th class="px-6 py-3 font-medium">Keterangan</th>
                        <th class="px-6 py-3 text-right font-medium">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($journals as $header)
                        @foreach($header->details as $detail)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 whitespace-nowrap">{{ date('d/m/Y', strtotime($header->transaction_date)) }}</td>
                            <td class="px-6 py-3 font-bold text-gray-700 font-mono">{{ $header->voucher_no }}</td>
                            
                            <td class="px-6 py-3 text-red-600">
                                @if($detail->creditAccount)
                                    <span class="font-mono text-xs text-gray-400">[{{ $detail->creditAccount->code }}]</span> 
                                    <span class="font-bold">{{ $detail->creditAccount->name }}</span>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-6 py-3 text-green-600">
                                @if($detail->debitAccount)
                                    <span class="font-mono text-xs text-gray-400">[{{ $detail->debitAccount->code }}]</span> 
                                    <span class="font-bold">{{ $detail->debitAccount->name }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            
                            <td class="px-6 py-3 text-gray-500 italic">
                                {{ Str::limit($header->description, 30) }}
                                @if($detail->memo) <br><small class="text-xs text-gray-400">({{ $detail->memo }})</small> @endif
                            </td>

                            <td class="px-6 py-3 text-right font-bold text-gray-800">Rp {{ number_format($detail->amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-book text-4xl mb-3 text-gray-300"></i><br>
                                Tidak ada data jurnal pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($journals->count() > 0)
                <tfoot class="bg-gray-100 font-bold text-gray-700">
                    <tr>
                        <td colspan="5" class="px-6 py-3 text-right uppercase">Total Transaksi:</td>
                        <td class="px-6 py-3 text-right text-gray-900 text-lg">Rp {{ number_format($journals->sum('total_amount'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-layout>