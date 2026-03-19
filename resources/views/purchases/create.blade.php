<x-layout>
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p class="font-bold">Gagal Menyimpan!</p>
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
    <div id="successModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 w-full max-w-sm text-center border-4 border-blue-600">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Sukses!</h3>
            <p class="text-gray-500 mb-4">{{ session('success') }}</p>
            <button onclick="document.getElementById('successModal').style.display='none'" class="w-full bg-blue-600 text-white font-bold py-2 rounded">OK</button>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-center mb-2">
        <h2 class="text-xl font-bold text-gray-700"><i class="fas fa-shopping-cart mr-2"></i>Entry Data Pembelian</h2>
        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-red-600 hover:text-red-800"><i class="fas fa-times-circle mr-1"></i> Tutup</a>
    </div>

    <div class="bg-gray-300 p-1 rounded border border-gray-500 shadow-xl">
        
        <div class="bg-gradient-to-b from-gray-200 to-gray-400 border border-gray-400 p-1 rounded-t mb-1 flex justify-between items-center">
            <span class="text-xs font-bold text-gray-700 px-2">PEMBELIAN</span>
        </div>

        <form action="{{ route('purchases.store') }}" method="POST" class="bg-gray-200 p-2 border border-gray-400 rounded">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1 mb-2 text-xs">
                
                <div class="space-y-1">
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">No Faktur</label>
                        <input type="text" name="purchase_code" value="{{ $code }}" class="flex-1 border border-gray-400 bg-gray-100 px-2 py-0.5" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Jenis Transaksi</label>
                        <select name="purchase_category_id" class="flex-1 border border-gray-400 px-2 py-0.5" required>
                            <option value="">-- Pilih --</option>
                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Tgl Faktur</label>
                        <input type="date" name="purchase_date" id="trxDate" value="{{ date('Y-m-d') }}" class="w-32 border border-gray-400 px-2 py-0.5" onchange="calcDueDate()">
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Lama Kredit</label>
                        <div class="flex items-center">
                            <input type="number" name="credit_days" id="creditDays" value="0" class="w-16 border border-gray-400 px-2 py-0.5 text-right" oninput="calcDueDate()">
                            <span class="ml-2">Hari</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Tgl Overdue</label>
                        <input type="date" name="due_date" id="dueDate" class="w-32 border border-gray-400 bg-gray-100 px-2 py-0.5" readonly>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Nomor P.O</label>
                        <input type="text" name="purchase_order_number" class="flex-1 border border-gray-400 px-2 py-0.5">
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">PPn (%)</label>
                        <input type="number" name="tax_rate" id="taxPercent" value="0" class="w-16 border border-gray-400 px-2 py-0.5 text-right" oninput="calculateFooter()">
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Nomor Pajak</label>
                        <input type="text" name="tax_number" class="flex-1 border border-gray-400 px-2 py-0.5">
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Nota Supplier</label>
                        <input type="text" name="supplier_fax" class="flex-1 border border-gray-400 px-2 py-0.5">
                    </div>
                    <div class="flex items-center">
    <label class="w-24 font-bold text-gray-800">Supplier</label>
    <div class="flex flex-1 gap-1">
        <input type="hidden" name="supplier_id" id="supplierId" required>
        <input type="text" id="supplierDisplay" class="flex-1 border border-gray-400 bg-gray-100 px-2 py-0.5 font-bold" readonly placeholder="Pilih Supplier...">
        <button type="button" onclick="openSupModal()" class="bg-blue-700 text-white px-3 text-xs rounded hover:bg-blue-800">
            <i class="fas fa-search"></i> Cari
        </button>
    </div>
</div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Lokasi Gudang</label>
                        <select name="warehouse_id" class="flex-1 border border-gray-400 px-2 py-0.5" required>
                            @foreach($warehouses as $wh) <option value="{{ $wh->id }}">{{ $wh->name }}</option> @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="border border-gray-500 bg-white mb-2 overflow-x-auto" style="min-height: 250px;">
                <table class="w-full text-xs border-collapse" id="itemTable">
                    <thead class="bg-blue-800 text-white font-bold">
                        <tr>
                            <th class="p-1 border border-gray-400 w-8">No</th>
                            <th class="p-1 border border-gray-400 min-w-[200px]">Nama Barang</th>
                            <th class="p-1 border border-gray-400 w-16">Satuan</th>
                            <th class="p-1 border border-gray-400 w-16">Qty</th>
                            <th class="p-1 border border-gray-400 w-24">Harga</th>
                            <th class="p-1 border border-gray-400 w-10">% 1</th>
                            <th class="p-1 border border-gray-400 w-10">% 2</th>
                            <th class="p-1 border border-gray-400 w-24">Disc Rp</th>
                            <th class="p-1 border border-gray-400 w-24">SubTotal</th>
                            <th class="p-1 border border-gray-400 w-24">Modal</th>
                            <th class="p-1 border border-gray-400 w-8">#</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        </tbody>
                </table>
                <button type="button" onclick="addRow()" class="m-2 bg-gray-200 border border-gray-400 px-2 py-1 text-xs hover:bg-gray-300">+ Tambah Baris (F2)</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-400 pt-2 text-xs">
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-gray-100 border border-gray-400 px-4 py-2 font-bold hover:bg-gray-200 shadow flex items-center">
                        <i class="fas fa-save mr-1 text-blue-600"></i> Simpan
                    </button>
                    <button type="reset" class="bg-gray-100 border border-gray-400 px-4 py-2 font-bold hover:bg-gray-200 shadow flex items-center">
                        <i class="fas fa-trash mr-1 text-red-600"></i> Hapus
                    </button>
                    <button type="button" class="bg-gray-100 border border-gray-400 px-4 py-2 font-bold hover:bg-gray-200 shadow flex items-center">
                        <i class="fas fa-print mr-1 text-gray-600"></i> Cetak
                    </button>
                    <a href="#" class="bg-gray-100 border border-gray-400 px-4 py-2 font-bold hover:bg-gray-200 shadow flex items-center text-blue-800">
                        <i class="fas fa-door-open mr-1"></i> Tutup
                    </a>
                </div>

                <div class="space-y-1 bg-gray-100 p-2 border border-gray-300">
                    <div class="flex justify-between items-center">
                        <label class="w-40">Subtotal</label>
                        <input type="text" id="dispSubtotal" class="w-32 text-right bg-gray-200 px-1 border border-gray-400" readonly value="0">
                        <input type="hidden" name="subtotal_hidden" id="subtotalHidden">
                    </div>
                    <div class="flex justify-between items-center">
                        <label class="w-40">Subtotal + PPn</label>
                        <input type="text" name="tax_amount" id="taxAmount" class="w-32 text-right bg-gray-200 px-1 border border-gray-400" readonly value="0">
                    </div>
                    <div class="flex justify-between items-center">
                        <label class="w-40">Biaya Ekspedisi</label>
                        <input type="number" name="shipping_cost" id="shipCost" class="w-32 text-right bg-white px-1 border border-gray-400" value="0" oninput="calculateFooter()">
                    </div>
                    <div class="flex justify-between items-center">
                        <label class="w-40">Biaya Lain</label>
                        <input type="number" name="other_expense" id="otherCost" class="w-32 text-right bg-white px-1 border border-gray-400" value="0" oninput="calculateFooter()">
                    </div>
                    <div class="flex justify-between items-center">
                        <label class="w-40">Diskon ( % / Rp )</label>
                        <div class="flex gap-1 w-42">
                            <input type="number" name="discount_percent" id="discPct" class="w-10 text-center bg-white border border-gray-400" value="0" oninput="calculateFooter()">
                            <input type="number" name="discount_amount" id="discAmt" class="w-32 text-right bg-white border border-gray-400" value="0" oninput="calculateFooter()">
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <label class="w-40 font-bold text-red-600">Dibayar / Uang Muka</label>
                        <input type="number" name="done_payment" id="downPayment" class="w-32 text-right bg-white px-1 border border-red-300 font-bold" value="0" oninput="calculateFooter()">
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-300 pt-1 font-bold">
                        <label class="w-40">Saldo Terhutang</label>
                        <input type="text" id="dispGrandTotal" class="w-32 text-right bg-yellow-100 px-1 border border-gray-400 text-blue-800" readonly value="0">
                        <input type="hidden" name="grand_total_hidden" id="grandTotalHidden">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const products = @json($products);
        let rowCount = 0;

        function openSupModal() {
    document.getElementById('supplierModal').classList.remove('hidden');
    document.getElementById('searchSup').focus();
}

/** Menutup Modal Supplier */
function closeSupModal() {
    document.getElementById('supplierModal').classList.add('hidden');
}

/** Memasukkan data supplier terpilih ke dalam form */
function selectSupplier(id, name) {
    document.getElementById('supplierId').value = id;
    document.getElementById('supplierDisplay').value = name;
    closeSupModal();
}

/** Fitur Pencarian Live di dalam tabel modal */
function filterSupTable() {
    const input = document.getElementById("searchSup").value.toLowerCase();
    const rows = document.querySelectorAll("#supTableBody tr");
    
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

// Menutup modal jika user mengklik area di luar kotak modal
window.onclick = function(event) {
    const modal = document.getElementById('supplierModal');
    if (event.target == modal) {
        closeSupModal();
    }
}

        function calcDueDate() {
            const dateStr = document.getElementById('trxDate').value;
            const days = parseInt(document.getElementById('creditDays').value) || 0;
            if(dateStr) {
                const date = new Date(dateStr);
                date.setDate(date.getDate() + days);
                document.getElementById('dueDate').value = date.toISOString().split('T')[0];
            }
        }

        function reindexRows() {
    const rows = document.querySelectorAll('#tableBody tr');
    rows.forEach((tr, index) => {
        // Update nomor urut di kolom pertama (index + 1)
        tr.querySelector('td:first-child').innerText = index + 1;
        
        // Opsional: Jika Anda menggunakan index array pada atribut name input (misal products[0][id])
        // Anda perlu mereset atribut name agar urut juga (penting untuk validasi Laravel)
        // tr.querySelectorAll('input, select').forEach(input => {
        //    if(input.name) {
        //        input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
        //    }
        // });
    });
    
    // Update variabel global rowCount agar sinkron dengan jumlah baris saat ini
    rowCount = rows.length;
}
document.addEventListener('keydown', function(e) {
        
        // 1. Shortcut F2 untuk Tambah Baris Manual
        if(e.key === 'F2') { 
            e.preventDefault(); 
            addRow(); 
            return;
        }

        // 2. Handle Tombol Enter
        if (e.key === 'Enter') {
            const target = e.target;

            // Jangan ganggu Enter di Textarea atau Button
            if (target.tagName === 'TEXTAREA' || target.tagName === 'BUTTON') return;

            e.preventDefault();

            // --- DETEKSI APAKAH DI UJUNG BARIS TABEL? ---
            const currentTr = target.closest('tr');
            const tbody = document.getElementById('tableBody');

            // Cek apakah elemen ini ada di dalam Tabel Item
            if (currentTr && tbody.contains(currentTr)) {
                // Ambil semua input yang bisa diisi di baris INI saja
                // (Kecuali hidden, readonly, disabled)
                const inputsInRow = Array.from(currentTr.querySelectorAll('input:not([type="hidden"]):not([readonly]):not([disabled]), select:not([disabled])'));
                
                // Cek posisi index input saat ini di dalam baris
                const currentIndex = inputsInRow.indexOf(target);
                const isLastInput = currentIndex === inputsInRow.length - 1;

                // JIKA INI INPUT TERAKHIR DI BARIS ITU -> TAMBAH BARIS BARU
                if (isLastInput) {
                    addRow(); // 1. Buat baris baru
                    
                    // 2. Tunggu sebentar agar elemen baru muncul di DOM, lalu fokus
                    setTimeout(() => {
                        const newTr = tbody.lastElementChild; // Ambil baris paling bawah (baru)
                        if(newTr) {
                            // Cari input pertama yang bisa diedit di baris baru (biasanya Select Produk atau Kode)
                            const firstInput = newTr.querySelector('select:not([disabled]), input:not([readonly]):not([type="hidden"])');
                            if(firstInput) {
                                firstInput.focus();
                                // Opsional: jika itu input text, auto select
                                if(firstInput.tagName === 'INPUT') firstInput.select(); 
                            }
                        }
                    }, 50); // Delay 50ms cukup untuk render
                    
                    return; // Stop, jangan jalankan logika pindah kolom biasa
                }
            }

            // --- LOGIKA PINDAH KOLOM BIASA (NEXT FOCUS) ---
            // Jika bukan di ujung baris tabel, pindah ke kolom sebelah kanannya
            const form = target.form;
            if (!form) return;

            const selector = 'input:not([type="hidden"]):not([readonly]):not([disabled]), select:not([disabled]), button[type="submit"]';
            const focusable = Array.from(form.querySelectorAll(selector));
            const index = focusable.indexOf(target);

            if (index > -1 && index < focusable.length - 1) {
                const nextElement = focusable[index + 1];
                nextElement.focus();
                
                // Auto Select Text untuk kemudahan edit
                if (nextElement.tagName === 'INPUT' && nextElement.type !== 'date') {
                    nextElement.select();
                }
            }
        }
    });

        function addRow() {
            rowCount++;
            const tbody = document.getElementById('tableBody');
            
            let opts = '<option value="">- Pilih -</option>';
            products.forEach(p => opts += `<option value="${p.id}" data-unit="${p.unit ?? 'Pcs'}">${p.name}</option>`);

            const tr = document.createElement('tr');
            tr.className = "border-b hover:bg-blue-50";
            tr.innerHTML = `
                <td class="p-1 border border-gray-300 text-center">${rowCount}</td>
                <td class="p-1 border border-gray-300">
                    <select name="products[${rowCount}][id]" class="w-full border-none bg-transparent" onchange="setPrice(this)" required>${opts}</select>
                </td>
                <td class="p-1 border border-gray-300">
                    <input type="text" name="products[${rowCount}][unit]" class="w-full border-none bg-transparent text-center unit-disp" readonly>
                </td>
                <td class="p-1 border border-gray-300">
                    <input type="number" name="products[${rowCount}][qty]" class="w-full border-none bg-transparent text-center qty-inp" value="1" oninput="calcRow(this)" required>
                </td>
                <td class="p-1 border border-gray-300">
                    <input type="number" name="products[${rowCount}][price]" class="w-full border-none bg-transparent text-right price-inp" oninput="calcRow(this)" required>
                </td>
                <td class="p-1 border border-gray-300">
                    <input type="number" name="products[${rowCount}][disc_1]" class="w-full border-none bg-transparent text-center d1-inp" value="0" oninput="calcRow(this)">
                </td>
                <td class="p-1 border border-gray-300">
                    <input type="number" name="products[${rowCount}][disc_2]" class="w-full border-none bg-transparent text-center d2-inp" value="0" oninput="calcRow(this)">
                </td>
                <td class="p-1 border border-gray-300">
                    <input type="number" name="products[${rowCount}][disc_rp]" class="w-full border-none bg-transparent text-right drp-inp" value="0" oninput="calcRow(this)">
                </td>
                <td class="p-1 border border-gray-300">
                    <input type="text" class="w-full border-none bg-transparent text-right font-bold sub-disp" readonly value="0">
                    <input type="hidden" name="products[${rowCount}][subtotal_row]" class="sub-val">
                </td>
                <td class="p-1 border border-gray-300">
                    <input type="text" class="w-full border-none bg-transparent text-right modal-disp" readonly value="0">
                </td>
                <td class="p-1 border border-gray-300 text-center">
                    <button type="button" onclick="delRow(this)" class="text-red-500">x</button>
                </td>
            `;
            tbody.appendChild(tr);
            calculateFooter();
            reindexRows();
        }

        function setPrice(sel) {
            const tr = sel.closest('tr');
            const opt = sel.options[sel.selectedIndex];
          
            tr.querySelector('.unit-disp').value = opt.getAttribute('data-unit') || 'Pcs';
            calcRow(sel);
        }

        function calcRow(el) {
            const tr = el.closest('tr');
            const qty = parseFloat(tr.querySelector('.qty-inp').value) || 0;
            const price = parseFloat(tr.querySelector('.price-inp').value) || 0;
            const d1 = parseFloat(tr.querySelector('.d1-inp').value) || 0;
            const d2 = parseFloat(tr.querySelector('.d2-inp').value) || 0;
            const drp = parseFloat(tr.querySelector('.drp-inp').value) || 0;

            let total = qty * price;
            if(d1 > 0) total -= total * (d1/100);
            if(d2 > 0) total -= total * (d2/100);
            total -= drp;

            tr.querySelector('.sub-disp').value = fmt(total);
            tr.querySelector('.sub-val').value = total;
            tr.querySelector('.modal-disp').value = fmt(price); // Info Modal = Harga Beli
            calculateFooter();
        }

        function delRow(btn) {
            btn.closest('tr').remove();
            calculateFooter();
            reindexRows();
        }

        function calculateFooter() {
    let sub = 0;
    // Hitung total semua subtotal baris
    document.querySelectorAll('.sub-val').forEach(inp => {
        let val = parseFloat(inp.value);
        if (!isNaN(val)) sub += val;
    });

    // Ambil nilai-nilai footer
    const taxP = parseFloat(document.getElementById('taxPercent').value) || 0;
    
    // Diskon
    const dPct = parseFloat(document.getElementById('discPct').value) || 0;
    let dAmt = parseFloat(document.getElementById('discAmt').value) || 0;
    
    // Jika ada diskon persen, update diskon rupiah
    if(dPct > 0) {
        dAmt = sub * (dPct / 100);
        document.getElementById('discAmt').value = dAmt; // Update tampilan input
    }

    // Hitung PPN
    const taxVal = sub * (taxP / 100);
    const subPlusTax = sub + taxVal;

    // Biaya Lain
    const ship = parseFloat(document.getElementById('shipCost').value) || 0;
    const other = parseFloat(document.getElementById('otherCost').value) || 0;
    const dp = parseFloat(document.getElementById('downPayment').value) || 0;

    // Rumus Grand Total: (Subtotal + PPN + Biaya) - Diskon
    const grand = subPlusTax + ship + other - dAmt;
    const remain = grand - dp;

    // Update Tampilan (Format Ribuan)
    document.getElementById('dispSubtotal').value = fmt(sub);
    document.getElementById('taxAmount').value = fmt(subPlusTax);
    document.getElementById('dispGrandTotal').value = fmt(remain);

    // Update Input Hidden (Raw Number untuk Backend)
    document.getElementById('subtotalHidden').value = sub;
    // PENTING: Pastikan ini terisi!
    document.getElementById('grandTotalHidden').value = remain; 
}

        function fmt(n) {
            return new Intl.NumberFormat('id-ID').format(n);
        }

        window.onload = function() {
            calcDueDate();
            //addRow();
            calculateFooter();
        };
        
        document.addEventListener('keydown', e => {
            if(e.key === 'F2') { e.preventDefault(); addRow(); }
        });
    </script>
    <div id="supplierModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 overflow-hidden">
        <div class="bg-blue-800 text-white p-3 flex justify-between items-center">
            <h3 class="font-bold"><i class="fas fa-truck mr-2"></i>Pilih Supplier</h3>
            <button type="button" onclick="closeSupModal()" class="text-white text-2xl leading-none">&times;</button>
        </div>
        <div class="p-4">
            <input type="text" id="searchSup" placeholder="Cari Nama / Alamat / Kota Supplier..." 
                   class="w-full border border-gray-300 rounded px-3 py-2 mb-4 text-sm focus:ring-2 focus:ring-blue-500" 
                   onkeyup="filterSupTable()">
            <div class="max-h-80 overflow-y-auto border border-gray-200 rounded">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="p-2 border">Nama Supplier</th>
                            <th class="p-2 border">Kota</th>
                            <th class="p-2 border">Alamat</th>
                            <th class="p-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="supTableBody">
                        @foreach($suppliers as $sup)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="p-2 border font-bold text-gray-800">{{ $sup->name }}</td>
                            <td class="p-2 border">{{ $sup->city ?? '-' }}</td>
                            <td class="p-2 border text-gray-500 text-[10px]">{{ $sup->address ?? '-' }}</td>
                            <td class="p-2 border text-center">
                                <button type="button" 
                                    onclick="selectSupplier('{{ $sup->id }}', '{{ $sup->name }}')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded shadow-sm">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-layout>