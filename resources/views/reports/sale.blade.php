<x-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Laporan Penjualan</h2>
            <p class="text-gray-600">Rincian transaksi penjualan per barang.</p>
        </div>
        
        <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md mb-6 border-l-4 border-indigo-500">
        <form action="{{ route('reports.sale') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border-gray-300 rounded focus:ring-indigo-500">
            </div>
            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border-gray-300 rounded focus:ring-indigo-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded font-bold hover:bg-indigo-700 transition">Filter</button>
                <a href="{{ route('reports.sale') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">Reset</a>
            </div>
            <div class="ml-auto">
                <a href="{{ route('reports.sale.export', request()->query()) }}" class="bg-green-600 text-white px-5 py-2 rounded font-bold hover:bg-green-700 transition flex items-center shadow-lg">
                    <i class="fas fa-file-excel mr-2"></i> Download Excel
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse border border-gray-400">
                <thead class="bg-gray-300 text-gray-900 font-bold uppercase border-b-2 border-gray-800">
                    <tr>
                        <th class="px-2 py-2 border border-gray-400 w-10 text-center">No</th>
                        <th class="px-2 py-2 border border-gray-400 whitespace-nowrap w-24">Tanggal</th>
                        <th class="px-2 py-2 border border-gray-400 w-32">Nota</th>
                        <th class="px-2 py-2 border border-gray-400 w-32">Pelanggan</th>
                        <th class="px-2 py-2 border border-gray-400">Kode Brg</th>
                        <th class="px-2 py-2 border border-gray-400">Nama Barang</th>
                        <th class="px-2 py-2 border border-gray-400 text-center">Qty</th>
                        <th class="px-2 py-2 border border-gray-400 text-right">Harga</th>
                        <th class="px-2 py-2 border border-gray-400 text-right">Jumlah</th>
                        <th class="px-2 py-2 border border-gray-400 text-right">Tot. Diskon</th>
                        <th class="px-2 py-2 border border-gray-400 text-right">Netto</th>
                        <th class="px-2 py-2 border border-gray-400">Sales</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php 
                        $no = 1; 
                        $totalNetto = 0; 
                        $lastInvoice = null; 
                    @endphp

                    @forelse($sales as $item)
                        @php
                            $trx = $item->saleTransaction;
                            
                            // Hitungan
                            $gross = $item->quantity * $item->price;
                            $disc1_rp = $gross * (($item->disc_1 ?? 0) / 100);
                            $gross_after_d1 = $gross - $disc1_rp;
                            $disc2_rp = $gross_after_d1 * (($item->disc_2 ?? 0) / 100);
                            $total_disc = $disc1_rp + $disc2_rp + ($item->disc_rp ?? 0);
                            $netto = $item->subtotal; 
                            $totalNetto += $netto;

                            // Cek Grouping
                            $currentInvoice = $trx->invoice_code;
                            $isNewGroup = ($currentInvoice !== $lastInvoice);
                        @endphp

                        {{-- JEDA ANTAR TRANSAKSI (Baris Kosong Abu-abu) --}}
                        @if($isNewGroup && $lastInvoice !== null)
                            <tr class="bg-gray-300 h-2 border-t border-b border-gray-400">
                                <td colspan="12"></td>
                            </tr>
                        @endif

                        <tr class="hover:bg-gray-50 transition border-r border-l border-gray-400 {{ $isNewGroup ? 'border-t border-gray-400' : '' }}">
                            {{-- KOLOM HEADER (Hanya muncul jika Faktur Baru) --}}
                            <td class="px-2 py-1 text-center border-r border-gray-300 align-top font-bold">
                                {{ $isNewGroup ? $no++ : '' }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap border-r border-gray-300 align-top">
                                {{ $isNewGroup ? date('d-m-Y', strtotime($trx->transaction_date)) : '' }}
                            </td>
                            <td class="px-2 py-1 font-bold border-r border-gray-300 align-top text-blue-800">
                                {{ $isNewGroup ? $trx->invoice_code : '' }}
                            </td>
                            <td class="px-2 py-1 border-r border-gray-300 align-top font-bold text-gray-700">
                                {{ $isNewGroup ? ($trx->customer->name ?? 'Umum') : '' }}
                            </td>

                            {{-- KOLOM DETAIL (Selalu Muncul) --}}
                            <td class="px-2 py-1 text-xs font-mono border-r border-gray-300">
                                {{ $item->product->code ?? '-' }}
                            </td>
                            <td class="px-2 py-1 border-r border-gray-300">
                                {{ $item->product->name ?? '-' }}
                            </td>
                            <td class="px-2 py-1 text-center font-bold border-r border-gray-300">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-2 py-1 text-right border-r border-gray-300">
                                {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-1 text-right border-r border-gray-300">
                                {{ number_format($gross, 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-1 text-right text-red-600 border-r border-gray-300">
                                {{ number_format($total_disc, 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-1 text-right font-bold bg-gray-50 border-r border-gray-300">
                                {{ number_format($netto, 0, ',', '.') }}
                            </td>

                            {{-- KOLOM SALES (Header) --}}
                            <td class="px-2 py-1 text-xs border-gray-300 align-top">
                                {{ $isNewGroup ? ($trx->user->name ?? '-') : '' }}
                            </td>
                        </tr>

                        @php $lastInvoice = $currentInvoice; @endphp
                    @empty
                        <tr><td colspan="12" class="px-4 py-8 text-center text-gray-500">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
                @if($sales->count() > 0)
                <tfoot class="bg-gray-200 font-bold text-gray-800 border-t-2 border-gray-800">
                    <tr>
                        <td colspan="10" class="px-2 py-2 text-right">TOTAL NETTO PERIODE INI:</td>
                        <td class="px-2 py-2 text-right text-lg border-l border-r border-gray-400">Rp {{ number_format($totalNetto, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-layout>