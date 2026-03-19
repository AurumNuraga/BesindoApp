<x-layout>
    @if(session('success'))
    <div id="successModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 w-full max-w-sm text-center shadow-2xl border-4 border-emerald-500">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Data Tersimpan!</h3>
            <div class="text-6xl text-emerald-500 mb-4"><i class="fas fa-check-circle"></i></div>
            <button onclick="document.getElementById('successModal').style.display='none'" class="bg-emerald-600 text-white px-6 py-2 rounded font-bold shadow hover:bg-emerald-700 w-full">OK</button>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-center mb-2">
        <h2 class="text-xl font-bold text-gray-700"><i class="fas fa-desktop mr-2"></i>Entry Penerimaan Kas</h2>
        <a href="{{ route('finance.index') }}" class="text-sm font-bold text-red-600 hover:text-red-800"><i class="fas fa-times-circle mr-1"></i> Tutup / Kembali</a>
    </div>

    <div class="bg-gray-300 p-1 rounded border border-gray-500 shadow-xl">
        <div class="bg-gradient-to-b from-gray-100 to-gray-300 border border-gray-400 p-1 rounded-t mb-1">
            <span class="text-xs font-bold text-gray-600 px-2">FORMULIR BKM (BUKTI KAS MASUK)</span>
        </div>

        <form action="{{ route('finance.inflow.store') }}" method="POST" class="bg-gray-200 p-3 border border-gray-400 rounded">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-2 mb-4">
                <div class="space-y-1">
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">Nomor BKM</label>
                        <input type="text" name="inflow_number" value="{{ $code }}" class="flex-1 text-xs font-bold border border-gray-400 bg-yellow-50 px-2 py-1 shadow-inner" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">Tanggal BKM</label>
                        <input type="date" name="inflow_date" value="{{ date('Y-m-d') }}" class="flex-1 text-xs border border-gray-400 px-2 py-1 focus:bg-white bg-white shadow-inner">
                    </div>
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">Akun Kas (Debet)</label>
                        <select name="cash_account_id" class="flex-1 text-xs border border-gray-400 px-2 py-1 focus:bg-white bg-white shadow-inner" required>
                            <option value="">-- Masuk ke Kas --</option>
                            @foreach($cashAccounts as $cash)
                                <option value="{{ $cash->id }}">{{ $cash->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">Penyetor</label>
                        <input type="text" name="depositor_name" class="flex-1 text-xs border border-gray-400 px-2 py-1 focus:bg-white bg-white shadow-inner">
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center">
                        <label class="w-24 text-xs font-bold text-gray-800">Jenis BKM</label>
                        <select name="inflow_type" class="flex-1 text-xs border border-gray-400 px-2 py-1 bg-white shadow-inner">
                            <option value="KANTOR">KANTOR</option>
                            <option value="SALES">SALES</option>
                            <option value="PROJECT">PROJECT</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs font-bold text-gray-800">Sales</label>
                        <select name="sales_id" class="flex-1 text-xs border border-gray-400 px-2 py-1 bg-white shadow-inner">
                            <option value="">-- Non Sales --</option>
                            @foreach($salesUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs font-bold text-gray-800">Catatan</label>
                        <input type="text" name="global_note" class="flex-1 text-xs border border-gray-400 px-2 py-1 bg-white shadow-inner" placeholder="Keterangan umum...">
                    </div>
                    <div class="flex items-center mt-2">
                        <label class="w-24"></label>
                        <div class="flex items-center bg-white border border-gray-400 px-2 py-1 rounded shadow-inner">
                            <input type="checkbox" name="is_giro_cek" id="chkGiro" class="mr-2 h-4 w-4 text-emerald-600">
                            <label for="chkGiro" class="text-xs font-bold text-gray-700">Penerimaan Giro / Cek</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border border-gray-500 bg-white shadow-md mb-4 overflow-hidden">
                <div class="bg-gray-700 text-white text-xs font-bold py-1 px-2 flex justify-between items-center">
                    <span>RINCIAN PENERIMAAN</span>
                </div>
                
                <table class="w-full text-xs border-collapse">
                    <thead class="bg-gray-100 border-b border-gray-400">
                        <tr>
                            <th class="p-2 border-r w-10 text-center">#</th>
                            <th class="p-2 border-r w-1/3">Akun Pendapatan (Kredit)</th>
                            <th class="p-2 border-r">Keterangan Item</th>
                            <th class="p-2 border-r w-1/4 text-right">Jumlah (Rp)</th>
                            <th class="p-2 w-10 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="detailTableBody">
                        <tr class="bg-blue-50 border-b border-gray-200">
                            <td class="p-1 text-center">1</td>
                            <td class="p-1 border-r">
                                <select name="details[0][account_id]" class="w-full border border-gray-300 rounded px-1 py-1" required>
                                    <option value="">-- Pilih Akun --</option>
                                    @foreach($incomeAccounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-1 border-r">
                                <input type="text" name="details[0][description]" class="w-full border border-gray-300 rounded px-1 py-1" placeholder="Ket...">
                            </td>
                            <td class="p-1 border-r">
                                <input type="number" name="details[0][amount]" class="amount-input w-full text-right font-bold border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-emerald-400" required oninput="calculateTotal()">
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
                            <td colspan="3" class="p-2 text-right">TOTAL BKM :</td>
                            <td class="p-2 text-right text-emerald-700 text-sm" id="displayTotal">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex flex-col md:flex-row gap-4 items-end justify-between">
                <div class="w-full md:w-2/3">
                    <div class="text-xs font-bold text-gray-600 mb-1">Terbilang :</div>
                    <div class="bg-gray-100 border border-gray-400 p-2 text-xs italic font-serif text-gray-700 h-10 flex items-center shadow-inner" id="terbilangBox">
                        Nol Rupiah
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded text-sm font-bold shadow-lg border border-blue-800 flex items-center">
                        <i class="fas fa-save mr-2"></i> SIMPAN
                    </button>
                    <a href="{{ route('finance.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm font-bold shadow border border-gray-700">
                        BATAL
                    </a>
                </div>
            </div>

        </form>
    </div>

    <div class="mt-6 border border-gray-400 rounded bg-white shadow-lg">
        <div class="bg-gray-200 px-3 py-2 border-b border-gray-400 font-bold text-xs text-gray-700">
            RIWAYAT BKM TERAKHIR
        </div>
        <table class="w-full text-xs text-left">
            <thead class="bg-gray-100 border-b border-gray-300">
                <tr>
                    <th class="p-2 border-r">No BKM</th>
                    <th class="p-2 border-r">Tanggal</th>
                    <th class="p-2 border-r">Kas Masuk</th>
                    <th class="p-2 border-r">Ket. Umum</th>
                    <th class="p-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($inflows as $item)
                <tr class="hover:bg-blue-50 transition">
                    <td class="p-2 border-r font-mono font-bold text-blue-700">{{ $item->inflow_number }}</td>
                    <td class="p-2 border-r">{{ date('d/m/Y', strtotime($item->inflow_date)) }}</td>
                    <td class="p-2 border-r">{{ $item->cashAccount->name }}</td>
                    <td class="p-2 border-r text-gray-500 italic">{{ Str::limit($item->global_note, 40) }}</td>
                    <td class="p-2 text-right font-bold">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-2 bg-gray-50">
            {{ $inflows->links() }}
        </div>
    </div>

    <script>
        let rowCount = 1;
        const accountOptions = `
            <option value="">-- Pilih Akun --</option>
            @foreach($incomeAccounts as $acc)
                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
            @endforeach
        `;

        function reindexRows() {
            const tbody = document.getElementById('detailTableBody');
            const rows = tbody.querySelectorAll('tr');
            
            rows.forEach((tr, index) => {
                tr.querySelector('td:first-child').innerText = index + 1;
                const inputs = tr.querySelectorAll('select, input');
                inputs.forEach(input => {
                    if(input.name) {
                        input.name = input.name.replace(/details\[\d+\]/, `details[${index}]`);
                    }
                });
            });
        }

        function addRow() {
            const tbody = document.getElementById('detailTableBody');
            const tr = document.createElement('tr');
            tr.className = "bg-white border-b border-gray-200 hover:bg-gray-50";
            
            // Gunakan index sementara 999
            tr.innerHTML = `
                <td class="p-1 text-center"></td>
                <td class="p-1 border-r">
                    <select name="details[999][account_id]" class="w-full border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-emerald-400 focus:outline-none" required>
                        ${accountOptions}
                    </select>
                </td>
                <td class="p-1 border-r">
                    <input type="text" name="details[999][description]" class="w-full border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-emerald-400 focus:outline-none" placeholder="Ket...">
                </td>
                <td class="p-1 border-r">
                    <input type="number" name="details[999][amount]" class="amount-input w-full text-right font-bold border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-emerald-400 focus:outline-none" required oninput="calculateTotal()">
                </td>
                <td class="p-1 text-center">
                    <button type="button" class="text-red-500 hover:text-red-700" onclick="removeRow(this)">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            reindexRows(); // Panggil reindex setelah tambah
        }

        function removeRow(btn) {
            const tbody = document.getElementById('detailTableBody');
            if (tbody.rows.length > 1) {
                btn.closest('tr').remove();
                calculateTotal();
                reindexRows(); // Panggil reindex setelah hapus
            } else {
                alert("Minimal harus ada satu baris rincian.");
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

        // --- LOGIKA KEYBOARD ENTER (SAMA SEPERTI BKK) ---
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const target = e.target;
                if (target.tagName === 'TEXTAREA' || target.type === 'submit') return;
                e.preventDefault();

                const currentTr = target.closest('#detailTableBody tr');

                if (currentTr) {
                    const rowInputs = Array.from(currentTr.querySelectorAll('select, input:not([type="hidden"]):not([readonly])'));
                    const currentIndex = rowInputs.indexOf(target);

                    // 1. Pindah Kanan
                    if (currentIndex < rowInputs.length - 1) {
                        const nextInput = rowInputs[currentIndex + 1];
                        nextInput.focus();
                        if(nextInput.tagName === 'INPUT') nextInput.select();
                        return; 
                    } 
                    
                    // 2. Jika Kolom Terakhir
                    const tbody = document.getElementById('detailTableBody');
                    const allRows = tbody.querySelectorAll('tr');
                    const isLastRow = (currentTr === allRows[allRows.length - 1]);

                    if (isLastRow) {
                        addRow(); // Tambah Baris Baru
                        setTimeout(() => {
                            const newRows = tbody.querySelectorAll('tr');
                            const newRow = newRows[newRows.length - 1];
                            const firstInput = newRow.querySelector('select, input');
                            if (firstInput) firstInput.focus();
                        }, 50);
                    } else {
                        // Pindah ke Baris Bawahnya
                        const nextRow = currentTr.nextElementSibling;
                        if(nextRow){
                            const firstInputNextRow = nextRow.querySelector('select, input');
                            if (firstInputNextRow) firstInputNextRow.focus();
                        }
                    }
                    return;
                }

                // Logika Header
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

        // Init pertama kali agar baris default ter-index dengan benar
        window.onload = function() {
            reindexRows();
        };
    </script>
</x-layout>