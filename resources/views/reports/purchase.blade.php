<x-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 decoration-2 decoration-gray-800">Laporan Pembelian</h2>
            <p class="text-gray-600">Rincian transaksi pembelian per barang.</p>
        </div>
        
        <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md mb-6 border-l-4 border-cyan-500">
        <form action="{{ route('reports.purchase') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border-gray-300 rounded focus:ring-cyan-500">
            </div>
            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border-gray-300 rounded focus:ring-cyan-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-cyan-600 text-white px-5 py-2 rounded font-bold hover:bg-cyan-700 transition">Filter</button>
                <a href="{{ route('reports.purchase') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">Reset</a>
            </div>
            <div class="ml-auto">
                <a href="{{ route('reports.purchase.export', request()->query()) }}" class="bg-green-600 text-white px-5 py-2 rounded font-bold hover:bg-green-700 transition flex items-center shadow-lg">
                    <i class="fas fa-file-excel mr-2"></i> Download Excel
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse border border-gray-400">
                <thead class="bg-cyan-300 text-gray-900 font-bold uppercase border-b-2 border-gray-800">
                    <tr>
                        <th class="px-2 py-2 border border-gray-400 w-10 text-center">No</th>
                        <th class="px-2 py-2 border border-gray-400 w-32">Nota Supplier</th>
                        <th class="px-2 py-2 border border-gray-400 whitespace-nowrap w-24">Tanggal</th>
                        <th class="px-2 py-2 border border-gray-400 w-40">SUPPLIER</th>
                        <th class="px-2 py-2 border border-gray-400">Nama Barang</th>
                        <th class="px-2 py-2 border border-gray-400 text-center w-12">Qty</th>
                        <th class="px-2 py-2 border border-gray-400 text-center w-12">Stn</th>
                        <th class="px-2 py-2 border border-gray-400 text-right w-24">Harga/@</th>
                        <th class="px-2 py-2 border border-gray-400 w-12">Disc %</th>
                        <th class="px-2 py-2 border border-gray-400 w-20">Disc Rp</th>
                        <th class="px-2 py-2 border border-gray-400 text-right w-24">Subtotal</th>
                        <th class="px-2 py-2 border border-gray-400 w-16">%Global</th>
                        <th class="px-2 py-2 border border-gray-400 text-right w-24">Netto</th>
                        <th class="px-2 py-2 border border-gray-400 w-32">Faktur</th>
                        <th class="px-2 py-2 border border-gray-400 w-32">Faktur Pajak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php 
                        $no = 1; 
                        $totalNetto = 0; 
                    @endphp

                    @forelse($purchases as $item)
                        @php
                            $trx = $item->purchaseTransaction;
                            
                            $gross = $item->quantity * $item->price;
                            $disc_val = $gross * (($item->disc_1 ?? 0) / 100);
                            $disc_rp = $item->disc_rp ?? 0;
                            
                            $subtotal = $gross - $disc_val - $disc_rp;
                            $totalNetto += $subtotal;
                        @endphp

                        <tr class="hover:bg-cyan-50 transition border-b border-gray-300">
                            <td class="px-2 py-1 text-center border-r border-gray-300  font-bold">{{ $no++ }}</td>
                            <td class="px-2 py-1 border-r border-gray-300">{{ $trx->supplier_invoice_number }}</td>
                            <td class="px-2 py-1 whitespace-nowrap border-r border-gray-300">{{ date('d-m-Y', strtotime($trx->purchase_date)) }}</td>
                            <td class="px-2 py-1 border-r border-gray-300 font-bold text-gray-700">{{ $trx->supplier->name ?? 'Umum' }}</td>
                            <td class="px-2 py-1 border-r border-gray-300">{{ $item->product->name ?? '-' }}</td>
                            <td class="px-2 py-1 text-center border-r border-gray-300 font-bold">{{ $item->quantity }}</td>
                            <td class="px-2 py-1 text-center border-r border-gray-300">{{ $item->unit ?? 'Pcs' }}</td>
                            <td class="px-2 py-1 text-right border-r border-gray-300">{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-2 py-1 text-center border-r border-gray-300">{{ $item->disc_1 > 0 ? $item->disc_1 : '' }}</td>
                            <td class="px-2 py-1 text-right border-r border-gray-300">{{ $disc_rp > 0 ? number_format($disc_rp, 0, ',', '.') : '' }}</td>
                            <td class="px-2 py-1 text-right border-r border-gray-300 font-bold">{{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="px-2 py-1 border-r border-gray-300"></td> <td class="px-2 py-1 text-right border-r border-gray-300 font-bold">{{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="px-2 py-1 border-r border-gray-300 text-xs">{{ $trx->purchase_code }}</td>
                            <td class="px-2 py-1 text-xs">{{ $trx->tax_number ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="px-4 py-8 text-center text-gray-500">Tidak ada data pembelian.</td>
                        </tr>
                    @endforelse
                </tbody>
                
                @if($purchases->count() > 0)
                <tfoot class="bg-gray-100 font-bold text-gray-800 border-t-2 border-gray-600">
                    <tr>
                        <td colspan="10" class="px-2 py-2 text-right border-r border-gray-400">TOTAL:</td>
                        <td class="px-2 py-2 text-right border-r border-gray-400">{{ number_format($totalNetto, 0, ',', '.') }}</td>
                        <td></td>
                        <td class="px-2 py-2 text-right border-r border-gray-400">{{ number_format($totalNetto, 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-layout>