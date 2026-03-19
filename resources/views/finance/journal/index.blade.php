<x-layout>
    @if(session('success'))
    <div id="successModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 w-full max-w-sm text-center shadow-2xl border-4 border-indigo-500">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Jurnal Tersimpan!</h3>
            <div class="text-6xl text-indigo-500 mb-4"><i class="fas fa-check-circle"></i></div>
            <button onclick="document.getElementById('successModal').style.display='none'" class="bg-indigo-600 text-white px-6 py-2 rounded font-bold shadow hover:bg-indigo-700 w-full">OK</button>
        </div>
    </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 font-bold rounded">{{ session('error') }}</div>
    @endif

    <div class="flex justify-between items-center mb-2">
        <h2 class="text-xl font-bold text-gray-700"><i class="fas fa-book-open mr-2"></i>Entry Jurnal Umum</h2>
        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-red-600 hover:text-red-800"><i class="fas fa-times-circle mr-1"></i> Tutup</a>
    </div>

    <div class="bg-gray-300 p-1 rounded border border-gray-500 shadow-xl">
        <div class="bg-gradient-to-b from-gray-200 to-gray-400 border border-gray-400 p-1 rounded-t mb-1 flex justify-between items-center">
            <span class="text-xs font-bold text-gray-700 px-2">INPUT TRANSAKSI</span>
        </div>

        <form action="{{ route('journal.store') }}" method="POST" class="bg-gray-200 p-3 border border-gray-400 rounded">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-2 mb-4">
                <div class="space-y-1">
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">Nomor Bukti</label>
                        <input type="text" name="voucher_no" value="{{ $code }}" class="flex-1 text-xs font-bold border border-gray-400 bg-yellow-50 px-2 py-1 shadow-inner" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">Tanggal Bukti</label>
                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" class="w-40 text-xs border border-gray-400 px-2 py-1 shadow-inner bg-white">
                    </div>
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">Deskripsi Umum</label>
                        <input type="text" name="description" class="flex-1 text-xs border border-gray-400 px-2 py-1 bg-white shadow-inner" placeholder="Catatan Jurnal...">
                    </div>
                </div>
            </div>

            <div class="border border-gray-500 bg-white shadow-md mb-4 overflow-hidden">
                <div class="bg-gray-700 text-white text-xs font-bold py-1 px-2 flex justify-between items-center">
                    <span>RINCIAN JURNAL</span>
                    <button type="button" onclick="addRow()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-2 py-0.5 rounded text-[10px] shadow">
                        <i class="fas fa-plus"></i> Tambah Baris
                    </button>
                </div>
                
                <table class="w-full text-xs border-collapse">
                    <thead class="bg-gray-100 border-b border-gray-400">
                        <tr>
                            <th class="p-2 border-r w-8 text-center">#</th>
                            <th class="p-2 border-r  w-1/4">Akun Kredit (Sumber)</th>
                            <th class="p-2 border-r  w-1/4">Akun Debit (Tujuan)</th>
                            <th class="p-2 border-r">Keterangan Baris</th>
                            <th class="p-2 border-r w-1/5 text-right">Jumlah (Rp)</th>
                            <th class="p-2 w-10 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="detailTableBody">
                        <tr class="bg-white border-b border-gray-200">
                            <td class="p-1 text-center">1</td>
                            <td class="p-1 border-r">
                                <select name="details[0][credit_account_id]" class="w-full border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-red-400" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-1 border-r">
                                <select name="details[0][debit_account_id]" class="w-full border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-green-400" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-1 border-r">
                                <input type="text" name="details[0][memo]" class="w-full border border-gray-300 rounded px-1 py-1" placeholder="Memo...">
                            </td>
                            <td class="p-1 border-r">
                                <input type="number" name="details[0][amount]" class="amount-input w-full text-right font-bold border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-indigo-400" required oninput="calculateTotal()">
                            </td>
                            <td class="p-1 text-center">
                                <button type="button" class="text-red-500 hover:text-red-700" onclick="removeRow(this)">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-200 font-bold text-gray-700">
                        <tr>
                            <td colspan="4" class="p-2 text-right">TOTAL TRANSAKSI :</td>
                            <td class="p-2 text-right text-indigo-700 text-sm" id="displayTotal">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex justify-between items-end">
                <div class="w-2/3 bg-gray-100 border border-gray-400 p-2 text-xs italic font-serif text-gray-700 h-8 flex items-center shadow-inner" id="terbilangBox">
                    Nol Rupiah
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded text-sm font-bold shadow-lg flex items-center">
                        <i class="fas fa-save mr-2"></i> SIMPAN
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-6 border border-gray-400 rounded bg-white shadow-lg">
        <div class="bg-gray-200 px-3 py-2 border-b border-gray-400 font-bold text-xs text-gray-700">
            RIWAYAT JURNAL TERAKHIR
        </div>
        <table class="w-full text-xs text-left">
            <thead class="bg-gray-100 border-b border-gray-300">
                <tr>
                    <th class="p-2 border-r w-32">No Bukti</th>
                    <th class="p-2 border-r w-24">Tanggal</th>
                    <th class="p-2 border-r">Detail (Sumber -> Tujuan)</th>
                    <th class="p-2 text-right w-32">Total Nilai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($journals as $item)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border-r font-mono font-bold text-gray-700 align-top">{{ $item->voucher_no }}</td>
                    <td class="p-2 border-r align-top">{{ date('d/m/Y', strtotime($item->transaction_date)) }}</td>
                    
                    <td class="p-2 border-r">
                        <ul class="list-disc ml-4 text-[10px] text-gray-600">
                            @foreach($item->details as $d)
                                <li>
                                    <span class="text-red-700 font-bold">{{ $d->creditAccount->name }}</span> 
                                    <i class="fas fa-arrow-right mx-1 text-gray-400"></i> 
                                    <span class="text-green-700 font-bold">{{ $d->debitAccount->name }}</span>
                                    <span class="ml-2 text-gray-800">(Rp {{ number_format($d->amount, 0, ',','.') }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    
                    <td class="p-2 text-right font-bold align-top">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-2 bg-gray-50 border-t border-gray-300">
            {{ $journals->links() }}
        </div>
    </div>

    <script>
        let rowCount = 1;
        // Template Opsi Akun
        const accountOptions = `
            <option value="">-- Pilih --</option>
            @foreach($accounts as $acc)
                <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
            @endforeach
        `;

        function reindexRows() {
            const rows = document.querySelectorAll('#detailTableBody tr');
            rows.forEach((tr, index) => {
                tr.querySelector('td:first-child').innerText = index + 1;
                
                // Update Name Attribute
                tr.querySelector('select[name*="[credit_account_id]"]').name = `details[${index}][credit_account_id]`;
                tr.querySelector('select[name*="[debit_account_id]"]').name = `details[${index}][debit_account_id]`;
                tr.querySelector('input[name*="[memo]"]').name = `details[${index}][memo]`;
                tr.querySelector('input[name*="[amount]"]').name = `details[${index}][amount]`;
            });
            rowCount = rows.length;
        }

        function addRow() {
            const tbody = document.getElementById('detailTableBody');
            const tr = document.createElement('tr');
            tr.className = "bg-white border-b border-gray-200 hover:bg-gray-50";
            
            tr.innerHTML = `
                <td class="p-1 text-center"></td>
                <td class="p-1 border-r">
                    <select name="details[999][credit_account_id]" class="w-full border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-red-400" required>
                        ${accountOptions}
                    </select>
                </td>
                <td class="p-1 border-r">
                    <select name="details[999][debit_account_id]" class="w-full border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-green-400" required>
                        ${accountOptions}
                    </select>
                </td>
                <td class="p-1 border-r">
                    <input type="text" name="details[999][memo]" class="w-full border border-gray-300 rounded px-1 py-1" placeholder="Memo...">
                </td>
                <td class="p-1 border-r">
                    <input type="number" name="details[999][amount]" class="amount-input w-full text-right font-bold border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-indigo-400" required oninput="calculateTotal()">
                </td>
                <td class="p-1 text-center">
                    <button type="button" class="text-red-500 hover:text-red-700" onclick="removeRow(this)">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            reindexRows();
        }

        function removeRow(btn) {
            const tbody = document.getElementById('detailTableBody');
            if (tbody.rows.length > 1) {
                btn.closest('tr').remove();
                calculateTotal();
                reindexRows();
            } else {
                alert("Minimal satu baris.");
            }
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.amount-input').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('displayTotal').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total);
            document.getElementById('terbilangBox').innerText = total > 0 ? terbilang(total) + " Rupiah" : "Nol Rupiah";
        }

        function terbilang(a){
            var bilangan = ['','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas'];
            var kalimat = "";
            if(a < 12) kalimat = bilangan[a];
            else if(a < 20) kalimat = bilangan[a-10]+" Belas";
            else if(a < 100) { var u = a/10; var d = parseInt(String(u).substr(0,1)); var b = a%10; kalimat = bilangan[d]+" Puluh "+bilangan[b]; }
            else if(a < 200) kalimat = "Seratus "+terbilang(a-100);
            else if(a < 1000) { var u = a/100; var d = parseInt(String(u).substr(0,1)); var b = a%100; kalimat = bilangan[d]+" Ratus "+terbilang(b); }
            else if(a < 2000) kalimat = "Seribu "+terbilang(a-1000);
            else if(a < 1000000) { var u = a/1000; var d = parseInt(String(u)); var b = a%1000; kalimat = terbilang(d)+" Ribu "+terbilang(b); }
            else if(a < 1000000000) { var u = a/1000000; var d = parseInt(String(u)); var b = a%1000000; kalimat = terbilang(d)+" Juta "+terbilang(b); }
            return kalimat;
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const target = e.target;
                if (target.tagName === 'TEXTAREA' || target.type === 'submit') return;
                e.preventDefault();

                const currentTr = target.closest('#detailTableBody tr');

                if (currentTr) {
                    const rowInputs = Array.from(currentTr.querySelectorAll('select, input:not([type="hidden"]):not([readonly])'));
                    const currentIndex = rowInputs.indexOf(target);

                    // Navigasi Kanan
                    if (currentIndex < rowInputs.length - 1) {
                        const nextInput = rowInputs[currentIndex + 1];
                        nextInput.focus();
                        if(nextInput.tagName === 'INPUT') nextInput.select();
                        return; 
                    } 
                    
                    // Navigasi Bawah / Baris Baru
                    const tbody = document.getElementById('detailTableBody');
                    const allRows = tbody.querySelectorAll('tr');
                    const isLastRow = (currentTr === allRows[allRows.length - 1]);

                    if (isLastRow) {
                        addRow(); 
                        setTimeout(() => {
                            const newRows = tbody.querySelectorAll('tr');
                            const newRow = newRows[newRows.length - 1];
                            const firstInput = newRow.querySelector('select, input');
                            if(firstInput) firstInput.focus();
                        }, 50);
                    } else {
                        const nextRow = currentTr.nextElementSibling;
                        if(nextRow){
                            const firstInputNextRow = nextRow.querySelector('select, input');
                            if (firstInputNextRow) firstInputNextRow.focus();
                        }
                    }
                    return;
                }

                // Navigasi Header
                const form = document.querySelector('form');
                const selector = 'input:not([type="hidden"]):not([readonly]), select, button[type="submit"]';
                const focusables = Array.from(form.querySelectorAll(selector));
                const index = focusables.indexOf(target);

                if (index > -1 && index < focusables.length - 1) {
                    const nextElement = focusables[index + 1];
                    nextElement.focus();
                    if (nextElement.tagName === 'INPUT') nextElement.select();
                }
            }
        });

        window.onload = function() {
            reindexRows();
        };
    </script>
</x-layout>