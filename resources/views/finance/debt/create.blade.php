<x-layout>
    @if(session('success'))
    <div id="successModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 w-full max-w-sm text-center shadow-2xl border-4 border-blue-600">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Data Tersimpan!</h3>
            <div class="text-6xl text-blue-600 mb-4"><i class="fas fa-check-circle"></i></div>
            <button onclick="document.getElementById('successModal').style.display='none'" class="bg-blue-600 text-white px-6 py-2 rounded font-bold shadow hover:bg-blue-700 w-full">OK</button>
        </div>
    </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 font-bold rounded">{{ session('error') }}</div>
    @endif

    <div class="flex justify-between items-center mb-2">
        <h2 class="text-xl font-bold text-gray-700"><i class="fas fa-desktop mr-2"></i>Entry Pelunasan Utang</h2>
        <a href="{{ route('finance.index') }}" class="text-sm font-bold text-red-600 hover:text-red-800"><i class="fas fa-times-circle mr-1"></i> Tutup</a>
    </div>

    <div class="bg-gray-300 p-1 rounded border border-gray-500 shadow-xl">
        <div class="bg-gradient-to-b from-gray-200 to-gray-400 border border-gray-400 p-1 rounded-t mb-1">
            <span class="text-xs font-bold text-gray-700 px-2">ENTRY PELUNASAN UTANG</span>
        </div>

        <form action="{{ route('finance.debt.store') }}" method="POST" class="bg-gray-200 p-3 border border-gray-400 rounded">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-2 mb-4">
                <div class="space-y-1">
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">NOMOR BUKTI</label>
                        <input type="text" name="payment_number" value="{{ $code }}" class="flex-1 text-xs font-bold border border-gray-400 bg-yellow-50 px-2 py-1 shadow-inner" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">TANGGAL</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-32 text-xs border border-gray-400 px-2 py-1 shadow-inner bg-white">
                    </div>
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">SUMBER KAS</label>
                        <select name="cash_account_id" class="flex-1 text-xs border border-gray-400 px-2 py-1 shadow-inner bg-white" required>
                            <option value="">-- Pilih Kas --</option>
                            @foreach($cashAccounts as $cash)
                                <option value="{{ $cash->id }}">{{ $cash->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">KODE SUPPLIER</label>
                        <select name="supplier_id" id="supplierSelect" class="flex-1 text-xs border border-gray-400 px-2 py-1 shadow-inner bg-white" onchange="resetTable()" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-32 text-xs font-bold text-gray-800">CATATAN</label>
                        <input type="text" name="global_note" class="flex-1 text-xs border border-gray-400 px-2 py-1 shadow-inner bg-white" placeholder="Keterangan umum...">
                    </div>
                    <div class="flex items-center mt-2">
                        <label class="w-32"></label>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_giro_cek" id="chkGiro" class="mr-2 h-4 w-4 text-blue-600">
                            <label for="chkGiro" class="text-xs font-bold text-gray-700">Giro / Cek</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border border-gray-500 bg-white shadow-md mb-4 overflow-hidden">
                <div class="bg-gray-700 text-white text-xs font-bold py-1 px-2 flex justify-between items-center">
                    <span>RINCIAN FAKTUR YANG DIBAYAR</span>
                </div>
                
                <table class="w-full text-xs border-collapse">
                    <thead class="bg-gray-100 border-b border-gray-400">
                        <tr>
                            <th class="p-2 border-r w-10 text-center">#</th>
                            <th class="p-2 border-r w-1/3">Nomor Faktur</th>
                            <th class="p-2 border-r text-right w-1/6">Sisa Hutang</th>
                            <th class="p-2 border-r text-right w-1/4">Jumlah Dibayar</th>
                            <th class="p-2 border-r">Keterangan</th>
                            <th class="p-2 w-10 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="detailTableBody">
                        </tbody>
                    <tfoot class="bg-gray-200 font-bold text-gray-700">
                        <tr>
                            <td colspan="3" class="p-2 text-right">TOTAL BAYAR :</td>
                            <td class="p-2 text-right text-blue-700 text-sm" id="displayTotal">Rp 0</td>
                            <td colspan="2"></td>
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
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded text-sm font-bold shadow-lg flex items-center">
                        <i class="fas fa-save mr-2"></i> SIMPAN
                    </button>
                    <a href="{{ route('finance.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm font-bold shadow">
                        BATAL
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div id="invoiceModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-4xl rounded shadow-xl border border-gray-500 overflow-hidden flex flex-col h-[500px]">
            <div class="bg-blue-700 px-4 py-2 flex justify-between items-center text-white">
                <h3 class="font-bold text-sm">PILIH FAKTUR HUTANG</h3>
                <button onclick="closeInvoiceModal()" class="text-xl font-bold">&times;</button>
            </div>
            <div class="p-0 overflow-y-auto flex-1 bg-gray-100">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-gray-200 border-b border-gray-400 sticky top-0">
                        <tr>
                            <th class="p-2 border-r">No Faktur</th>
                            <th class="p-2 border-r text-right">Total Tagihan</th>
                            <th class="p-2 border-r text-right">Sudah Bayar</th>
                            <th class="p-2 border-r text-right">Retur</th>
                            <th class="p-2 border-r text-right">Sisa Hutang</th>
                            <th class="p-2 text-center w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceListBody" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>
            <div class="bg-gray-200 px-4 py-2 border-t border-gray-400 text-right">
                <button onclick="closeInvoiceModal()" class="bg-gray-500 text-white px-4 py-1 rounded text-xs font-bold">Tutup (Esc)</button>
            </div>
        </div>
    </div>

    <script>
        let rowCount = 0;
        let currentRowIndex = -1; // Menyimpan index baris mana yang sedang memilih faktur

        function addRow() {
            const tbody = document.getElementById('detailTableBody');
            const tr = document.createElement('tr');
            tr.className = "bg-white border-b border-gray-200 hover:bg-gray-50";
            
            // Index 999 sementara, akan di-reindex
            tr.innerHTML = `
                <td class="p-1 text-center"></td>
                <td class="p-1 border-r">
                    <div class="flex gap-1">
                        <input type="hidden" name="details[999][invoice_id]" class="invoice-id">
                        <input type="text" class="w-full border border-gray-300 rounded px-1 py-1 bg-gray-50 invoice-code" readonly placeholder="Pilih Faktur..." onclick="openModalForRow(this)">
                        <button type="button" class="bg-gray-300 px-2 rounded font-bold text-xs" onclick="openModalForRow(this)">...</button>
                    </div>
                </td>
                <td class="p-1 border-r">
                    <input type="text" class="w-full text-right border border-gray-300 rounded px-1 py-1 bg-gray-100 remaining-debt" readonly value="0">
                </td>
                <td class="p-1 border-r">
                    <input type="number" name="details[999][amount]" class="amount-input w-full text-right font-bold border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-blue-400" required oninput="calculateTotal()">
                </td>
                <td class="p-1 border-r">
                    <input type="text" name="details[999][notes]" class="w-full border border-gray-300 rounded px-1 py-1" placeholder="Ket...">
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

        function reindexRows() {
            const rows = document.querySelectorAll('#detailTableBody tr');
            rows.forEach((tr, index) => {
                tr.querySelector('td:first-child').innerText = index + 1;
                
                // Update atribut name agar array urut
                tr.querySelector('.invoice-id').name = `details[${index}][invoice_id]`;
                tr.querySelector('.amount-input').name = `details[${index}][amount]`;
                tr.querySelector('input[name*="[notes]"]').name = `details[${index}][notes]`;
            });
            rowCount = rows.length;
        }

        function removeRow(btn) {
            const tbody = document.getElementById('detailTableBody');
            if (tbody.rows.length > 1) {
                btn.closest('tr').remove();
                calculateTotal();
                reindexRows();
            } else {
                alert("Minimal harus ada satu baris rincian.");
            }
        }

        function resetTable() {
            document.getElementById('detailTableBody').innerHTML = '';
            addRow(); // Tambah 1 baris kosong
            calculateTotal();
        }

        // --- MODAL LOGIC ---
        function openModalForRow(element) {
            const supplierId = document.getElementById('supplierSelect').value;
            if(!supplierId) { alert("Pilih Supplier dulu!"); return; }

            // Cari index baris tempat tombol diklik
            const tr = element.closest('tr');
            currentRowIndex = Array.from(tr.parentNode.children).indexOf(tr);

            document.getElementById('invoiceModal').classList.remove('hidden');
            document.getElementById('invoiceModal').classList.add('flex');
            
            // Fetch Data
            const tbody = document.getElementById('invoiceListBody');
            tbody.innerHTML = '<tr><td colspan="5" class="p-4 text-center">Memuat...</td></tr>';

            fetch(`/keuangan/utang/get-invoices/${supplierId}`)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';
                    if(data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="p-4 text-center">Tidak ada tagihan.</td></tr>'; return;
                    }
                    data.forEach(inv => {
                        const row = `
                            <tr class="hover:bg-blue-50 cursor-pointer" ondblclick='selectInvoice(${JSON.stringify(inv)})'>
                                <td class="p-2 border-r font-bold text-blue-800">${inv.text}</td>
                                <td class="p-2 border-r text-right">${fmt(inv.total)}</td>
                                <td class="p-2 border-r text-right text-green-600">${fmt(inv.paid)}</td>
                                <td class="p-2 border-r text-right">${fmt(inv.retur)}</td>
                                <td class="p-2 border-r text-right font-bold text-red-600">${fmt(inv.remaining)}</td>
                                <td class="p-2 text-center">
                                    <button type="button" onclick='selectInvoice(${JSON.stringify(inv)})' class="bg-blue-600 text-white px-2 py-0.5 rounded text-xs">Pilih</button>
                                </td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                });
        }

        function selectInvoice(inv) {
            const rows = document.querySelectorAll('#detailTableBody tr');
            const tr = rows[currentRowIndex];

            // Isi Data ke Baris yang Aktif
            tr.querySelector('.invoice-id').value = inv.id;
            tr.querySelector('.invoice-code').value = inv.text;
            tr.querySelector('.remaining-debt').value = fmt(inv.remaining); // Hanya display
            
            // Fokus ke input Amount
            const amountInput = tr.querySelector('.amount-input');
            amountInput.value = ""; // Reset nilai lama jika ada
            amountInput.focus();

            closeInvoiceModal();
        }

        function closeInvoiceModal() {
            document.getElementById('invoiceModal').classList.add('hidden');
            document.getElementById('invoiceModal').classList.remove('flex');
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.amount-input').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('displayTotal').innerText = fmt(total);
            document.getElementById('terbilangBox').innerText = total > 0 ? terbilang(total) + " Rupiah" : "Nol Rupiah";
        }

        function fmt(num) { return new Intl.NumberFormat('id-ID').format(num); }

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

        // Init
        window.onload = function() {
            addRow(); // Default 1 baris kosong saat load
        };

        // Keyboard Navigation (Enter Logic)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const target = e.target;
                if (target.tagName === 'TEXTAREA' || target.type === 'submit') return;
                e.preventDefault();

                const currentTr = target.closest('#detailTableBody tr');

                if (currentTr) {
                    const rowInputs = Array.from(currentTr.querySelectorAll('input:not([type="hidden"]):not([readonly])'));
                    const currentIndex = rowInputs.indexOf(target);

                    // Pindah Kanan
                    if (currentIndex < rowInputs.length - 1) {
                        const nextInput = rowInputs[currentIndex + 1];
                        nextInput.focus();
                        if(nextInput.tagName === 'INPUT') nextInput.select();
                        return; 
                    } 
                    
                    // Jika Kolom Terakhir (Amount / Notes)
                    const tbody = document.getElementById('detailTableBody');
                    const allRows = tbody.querySelectorAll('tr');
                    const isLastRow = (currentTr === allRows[allRows.length - 1]);

                    if (isLastRow) {
                        addRow(); 
                        setTimeout(() => {
                            const newRows = tbody.querySelectorAll('tr');
                            const newRow = newRows[newRows.length - 1];
                            // Coba buka modal otomatis untuk baris baru (Optional, UX bagus)
                            // newRow.querySelector('button').click(); 
                            // Atau sekedar fokus
                            const firstInput = newRow.querySelector('.invoice-code');
                            if(firstInput) { firstInput.focus(); openModalForRow(firstInput); }
                        }, 50);
                    } else {
                        const nextRow = currentTr.nextElementSibling;
                        if(nextRow){
                            const firstInputNextRow = nextRow.querySelector('.invoice-code');
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
            
            // Shortcut F1 untuk buka modal jika fokus di kolom faktur
            if (e.key === "F1") {
                const activeElement = document.activeElement;
                if(activeElement.classList.contains('invoice-code')) {
                    e.preventDefault();
                    openModalForRow(activeElement);
                }
            }
            if (e.key === "Escape") closeInvoiceModal();
        });
    </script>
</x-layout>