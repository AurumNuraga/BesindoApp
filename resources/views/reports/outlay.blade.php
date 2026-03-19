<x-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Laporan Pengeluaran Kas</h2>
            <p class="text-gray-600">Rekap biaya operasional dan pengeluaran lainnya.</p>
        </div>
        
        <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md mb-6 border-l-4 border-rose-500">
        <form action="{{ route('reports.outlay') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            
            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border-gray-300 rounded focus:ring-rose-500">
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border-gray-300 rounded focus:ring-rose-500">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-rose-600 text-white px-5 py-2 rounded font-bold hover:bg-rose-700 transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                
                <a href="{{ route('reports.outlay') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">
                    Reset
                </a>
            </div>

            <div class="ml-auto">
                <a href="{{ route('reports.outlay.export', request()->query()) }}" class="bg-green-600 text-white px-5 py-2 rounded font-bold hover:bg-green-700 transition flex items-center shadow-lg transform hover:-translate-y-1">
                    <i class="fas fa-file-excel mr-2"></i> Download Excel
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-800 text-white uppercase border-b border-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-center w-10">No</th>
                        <th class="px-4 py-3">Nomor BKK</th>
                        <th class="px-4 py-3">Tgl. BKK</th>
                        <th class="px-4 py-3">Akun Kas (K)</th>
                        <th class="px-4 py-3">Akun Biaya (D)</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                        <th class="px-4 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php 
                        $no = 1; 
                        $totalGlobal = 0; 
                    @endphp
                    
                    @forelse($outlays as $header)
                        @foreach($header->details as $detail)
                        @php $totalGlobal += $detail->amount; @endphp
                        <tr class="hover:bg-rose-50 transition border-b border-gray-200">
                            
                            <td class="px-4 py-2 text-center">{{ $no++ }}</td>

                            <td class="px-4 py-2 font-bold text-gray-800 font-mono">{{ $header->outlay_code }}</td>

                            <td class="px-4 py-2 whitespace-nowrap">{{ date('d-m-Y', strtotime($header->transaction_date)) }}</td>

                            <td class="px-4 py-2">
                                <span class="">{{ $header->cashAccount->name ?? 'KAS UMUM' }}</span>
                            </td>

                            <td class="px-4 py-2">
                                
                                    {{ $detail->outlayAccount->name ?? '-' }}
                      
                            </td>

                            <td class="px-4 py-2 text-right font-bold text-gray-900 border-l border-r border-gray-200 ">
                                {{ number_format($detail->amount, 2, ',', '.') }}
                            </td>

                            <td class="px-4 py-2 text-gray-800">
                                {{ $detail->notes ?? $header->global_note ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 italic">
                                Tidak ada data pengeluaran pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                
                @if($outlays->count() > 0)
                <tfoot class="bg-gray-300 font-bold text-gray-800 border-t-2 border-gray-400">
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-right">Total pengeluaran</td>
                        <td class="px-4 py-3 text-right border-l border-r border-gray-400">
                            {{ number_format($totalGlobal, 2, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-layout>