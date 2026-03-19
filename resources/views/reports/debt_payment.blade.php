<x-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 underline decoration-2 decoration-gray-800">DAFTAR PELUNASAN UTANG</h2>
            <p class="text-gray-600">Rekap riwayat pembayaran hutang ke supplier.</p>
        </div>
        
        <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md mb-6 border-l-4 border-green-500">
        <form action="{{ route('reports.debt_payment') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border-gray-300 rounded focus:ring-green-500">
            </div>
            <div class="w-full md:w-auto">
                <label class="block text-sm font-bold text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border-gray-300 rounded focus:ring-green-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded font-bold hover:bg-green-700 transition">Filter</button>
                <a href="{{ route('reports.debt_payment') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">Reset</a>
            </div>
            <div class="ml-auto">
                <a href="{{ route('reports.debt_payment.export', request()->query()) }}" class="bg-green-600 text-white px-5 py-2 rounded font-bold hover:bg-green-700 transition flex items-center shadow-lg">
                    <i class="fas fa-file-excel mr-2"></i> Download Excel
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden p-4">
        
        {{-- TABEL UTAMA --}}
        <table class="w-full text-xs text-left border-collapse border border-gray-400">
            {{-- HEADER KOLOM --}}
            <thead class="bg-white text-gray-900 font-bold uppercase border-b-2 border-gray-800">
                <tr>
                    <th class="px-2 py-2 border border-gray-400 w-10 text-center">NO</th>
                    <th class="px-2 py-2 border border-gray-400">NO. KAS</th>
                    <th class="px-2 py-2 border border-gray-400 w-24">TGL. FAKTUR</th>
                    <th class="px-2 py-2 border border-gray-400 w-32">NO. FAKTUR</th>
                    <th class="px-2 py-2 border border-gray-400 w-32">NO. NOTA</th>
                    <th class="px-2 py-2 border border-gray-400">NAMA SUPPLIER</th>
                    <th class="px-2 py-2 border border-gray-400 w-20">NO. CEK</th>
                    <th class="px-2 py-2 border border-gray-400 w-24">TGL. CAIR</th>
                    <th class="px-2 py-2 border border-gray-400 w-24">NAMA AKUN</th>
                    <th class="px-2 py-2 border border-gray-400 text-right w-32">JUMLAH</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php 
                    $grandTotal = 0; 
                    // Kelompokkan data per tanggal bayar agar mirip gambar
                    $grouped = $payments->groupBy('payment_date');
                @endphp

                @forelse($grouped as $date => $dailyPayments)
                    {{-- HEADER TANGGAL (HIJAU MUDA) --}}
                    <tr class="bg-green-100 font-bold border border-gray-400">
                        <td colspan="10" class="px-2 py-1 text-green-900">
                            {{ date('d-m-Y', strtotime($date)) }}
                        </td>
                    </tr>

                    @foreach($dailyPayments as $payment)
                        @php 
                            $no = 1; // Reset nomor per bukti bayar
                            $totalPerBukti = 0;
                        @endphp

                        @foreach($payment->details as $detail)
                            @php 
                                $trx = $detail->purchaseTransaction; 
                                $totalPerBukti += $detail->amount_paid;
                            @endphp
                            <tr class="hover:bg-gray-50 border-r border-l border-gray-400">
                                <td class="px-2 py-1 text-center border-r border-gray-300">{{ $no++ }}</td>
                                <td class="px-2 py-1 border-r border-gray-300 font-bold">{{ $payment->payment_number }}</td>
                                <td class="px-2 py-1 border-r border-gray-300">{{ date('d-m-Y', strtotime($trx->purchase_date)) }}</td>
                                <td class="px-2 py-1 border-r border-gray-300">{{ $trx->purchase_code }}</td>
                                <td class="px-2 py-1 border-r border-gray-300">{{ $trx->supplier_invoice_number ?? '-' }}</td>
                                <td class="px-2 py-1 border-r border-gray-300">{{ $payment->supplier->name }}</td>
                                <td class="px-2 py-1 border-r border-gray-300">{{ $payment->check_number ?? '' }}</td>
                                <td class="px-2 py-1 border-r border-gray-300">{{ $payment->check_date ? date('d-m-Y', strtotime($payment->check_date)) : '' }}</td>
                                <td class="px-2 py-1 border-r border-gray-300">{{ $payment->cashAccount->name ?? 'CASH' }}</td>
                                <td class="px-2 py-1 text-right border-r border-gray-300"></td> </tr>
                        @endforeach

                        {{-- BARIS TOTAL PER BUKTI BAYAR --}}
                        <tr class="border-b border-gray-400 font-bold">
                            <td colspan="8"></td>
                            <td class="px-2 py-1 text-right border-r border-l border-gray-400">TOTAL</td>
                            <td class="px-2 py-1 text-right border-r border-gray-400 bg-gray-50">
                                {{ number_format($totalPerBukti, 2, ',', '.') }}
                            </td>
                        </tr>
                        @php $grandTotal += $totalPerBukti; @endphp
                    @endforeach

                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-8 text-center text-gray-500">
                            Tidak ada data pelunasan utang.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($payments->count() > 0)
                <tfoot class="bg-gray-800 text-white font-bold">
                    <tr>
                        <td colspan="9" class="px-2 py-2 text-right">GRAND TOTAL:</td>
                        <td class="px-2 py-2 text-right text-lg">Rp {{ number_format($grandTotal, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</x-layout>