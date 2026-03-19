<x-layout>
    @if(session('success'))
    <div id="successModal" class="fixed inset-0 bg-giay-900 bg-opacity-70 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 w-full max-w-sm text-center border-4 border-blue-600">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Sukses!</h3>
            <p class="text-gray-500 mb-4">{{ session('success') }}</p>
            <button onclick="document.getElementById('successModal').style.display='none'" class="w-full bg-blue-600 text-white font-bold py-2 rounded">OK</button>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-center mb-2">
        <h2 class="text-xl font-bold text-gray-700"><i class="fas fa-undo-alt mr-2"></i>Entry Retur Pembelian</h2>
        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-red-600 hover:text-red-800"><i class="fas fa-times-circle mr-1"></i> Tutup</a>
    </div>

    <div class="bg-gray-300 p-1 rounded border border-gray-500 shadow-xl">
        
        <div class="bg-gradient-to-b from-gray-200 to-gray-400 border border-gray-400 p-1 rounded-t mb-1 flex justify-between items-center">
            <span class="text-xs font-bold text-gray-700 px-2">RETUR PEMBELIAN</span>
        </div>

        <form action="{{ route('purchases.return.store') }}" method="POST" class="bg-gray-200 p-2 border border-gray-400 rounded">
            @csrf
            
            <div class="grid grid-cols-12 gap-6 mb-2 text-xs">
                
                <div class="col-span-6 space-y-1">
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">No Retur</label>
                        <input type="text" name="return_number" value="{{ $code }}" class="w-40 border border-gray-400 bg-gray-100 px-2 py-0.5 font-bold" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Tgl Retur</label>
                        <input type="date" name="return_date" value="{{ date('Y-m-d') }}" class="w-40 border border-gray-400 px-2 py-0.5">
                    </div>
                    <div class="flex items-center">
    <label class="w-24 font-bold text-gray-800">Supplier</label>
    <div class="flex flex-1 gap-1">
        <input type="hidden" name="supplier_id" id="supId" required>
        <input type="text" id="supDisplay" class="flex-1 border border-gray-400 bg-gray-100 px-2 py-0.5 font-bold" readonly placeholder="Klik Cari Supplier...">
        <button type="button" onclick="openSupModal()" class="bg-blue-700 text-white px-3 text-xs rounded hover:bg-blue-800">
            <i class="fas fa-search"></i> Cari
        </button>
    </div>
</div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Alamat</label>
                        <input type="text" id="supAddr" class="w-full border border-gray-400 bg-gray-100 px-2 py-0.5" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Kota</label>
                        <input type="text" name="city" id="supCity" class="w-full border border-gray-400 bg-gray-100 px-2 py-0.5" readonly>
                    </div>
                </div>

                <div class="col-span-6 space-y-1">
                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Jenis Retur</label>
                        <select name="return_type" class="w-48 border border-gray-400 px-2 py-0.5">
                            <option value="Invoice">Dengan Nomor Faktur</option>
                        </select>
                        <div class="flex flex-1 justify-end items-center">
                            <label class="w-16 font-bold text-gray-800 text-right pr-2">PPn %</label>
                            <input type="text" id="taxPct" name="tax_pct" class="w-16 border border-gray-400 bg-gray-100 px-2 py-0.5 text-center" readonly value="0">
                        </div>
                    </div>
                    
                    <div class="flex items-center">
    <label class="w-24 font-bold text-gray-800">No Faktur</label>
    <div class="flex flex-1 gap-1">
        <input type="hidden" name="purchase_transaction_id" id="invId" required>
        <input type="text" id="invDisplay" class="flex-1 border border-gray-400 bg-gray-100 px-2 py-0.5 font-bold" readonly placeholder="Pilih Faktur...">
        <button type="button" id="btnSearchInv" onclick="openInvModal()" class="bg-blue-700 text-white px-3 text-xs rounded hover:bg-blue-800 disabled:bg-gray-400" disabled>
            <i class="fas fa-search"></i> Cari
        </button>
    </div>
</div>

                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Gudang</label>
                        <select name="warehouse_id" id="whSelect" class="w-full border border-gray-400 px-2 py-0.5">
                            @foreach($warehouses as $wh) <option value="{{ $wh->id }}">{{ $wh->name }}</option> @endforeach
                        </select>
                    </div>

                    <div class="flex items-center">
                        <label class="w-24 font-bold text-gray-800">Ket. Retur</label>
                        <input type="text" name="notes" class="w-full border border-gray-400 px-2 py-0.5">
                    </div>
                </div>
            </div>

            <div class="border border-gray-500 bg-white mb-2 overflow-x-auto" style="min-height: 250px;">
                <table class="w-full text-xs border-collapse table-fixed" id="itemTable">
                    <thead class="bg-blue-800 text-white font-bold">
                        <tr>
                            <th class="p-1 border border-gray-400 w-[30px]">No</th>
                            <th class="p-1 border border-gray-400 w-[100px]">Kode Barang</th>
                            <th class="p-1 border border-gray-400 w-[200px]">Nama Barang</th>
                            <th class="p-1 border border-gray-400 w-[60px]">Satuan</th>
                            <th class="p-1 border border-gray-400 w-[80px]">Harga</th>
                            <th class="p-1 border border-gray-400 w-[50px] bg-orange-200 text-black">Qty</th>
                            <th class="p-1 border border-gray-400 w-[40px]">% I</th>
                            <th class="p-1 border border-gray-400 w-[40px]">% II</th>
                            <th class="p-1 border border-gray-400 w-[70px]">Disc Rp</th>
                            <th class="p-1 border border-gray-400 w-[90px]">SubTotal</th>
                            <th class="p-1 border border-gray-400 w-[80px]">Modal</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        </tbody>
                </table>
            </div>

            <div class="grid grid-cols-12 gap-4 border-t border-gray-400 pt-2 text-xs">
                
                <div class="col-span-6 flex items-end gap-2 h-full pb-2">
                    <button type="submit" class="bg-gray-100 border border-gray-400 px-4 py-2 font-bold hover:bg-gray-200 shadow flex items-center min-w-[80px] justify-center">
                        <i class="fas fa-save mr-1 text-blue-600"></i> Simpan
                    </button>
                    <button type="reset" class="bg-gray-100 border border-gray-400 px-4 py-2 font-bold hover:bg-gray-200 shadow flex items-center min-w-[80px] justify-center">
                        <i class="fas fa-trash mr-1 text-red-600"></i> Hapus
                    </button>
                    <button type="button" class="bg-gray-100 border border-gray-400 px-4 py-2 font-bold hover:bg-gray-200 shadow flex items-center min-w-[80px] justify-center">
                        <i class="fas fa-print mr-1 text-gray-600"></i> Cetak
                    </button>
                    <a href="#" class="bg-gray-100 border border-gray-400 px-4 py-2 font-bold hover:bg-gray-200 shadow flex items-center min-w-[80px] justify-center text-blue-800">
                        <i class="fas fa-door-open mr-1"></i> Tutup
                    </a>
                </div>

                <div class="col-span-6 space-y-1 bg-gray-100 p-2 border border-gray-300">
                    <div class="flex justify-between items-center">
                        <label class="w-32 font-bold">Subtotal</label>
                        <input type="text" id="dispSubtotal" class="w-32 text-right bg-gray-200 px-1 border border-gray-400" readonly value="0">
                        <input type="hidden" name="subtotal_hidden" id="subtotalHidden">
                    </div>
                    <div class="flex justify-between items-center">
                        <label class="w-32">Subtotal + PPn</label>
                        <input type="text" id="subAfterTax" class="w-32 text-right bg-gray-200 px-1 border border-gray-400" readonly value="0">
                        <input type="hidden" name="tax_amount_hidden" id="taxAmountHidden">
                    </div>
                    <div class="flex justify-between items-center">
                        <label class="w-32">Biaya Ekspedisi</label>
                        <input type="number" name="shipping_cost" id="shipCost" class="w-32 text-right bg-white px-1 border border-gray-400" value="0" oninput="calculateFooter()">
                    </div>
                    <div class="flex justify-between items-center">
                        <label class="w-32">Biaya Lain</label>
                        <input type="number" name="other_cost" id="otherCost" class="w-32 text-right bg-white px-1 border border-gray-400" value="0" oninput="calculateFooter()">
                    </div>
                    <div class="flex justify-between items-center">
                        <label class="w-42">Diskon ( % / Rp )</label>
                        <div class="flex gap-1 w-32 justify-end">
                            <input type="number" name="global_discount_pct" id="discPct" class="w-10 text-center bg-white border border-gray-400" value="0" oninput="calculateFooter()">
                            <input type="number" name="global_discount_amount" id="discAmt" class="w-32 flex-1 text-right bg-white border border-gray-400" value="0" oninput="calculateFooter()">
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <label class="w-32">Dibayar / Uang Muka</label>
                        <input type="number" name="cash_refund" id="cashRefund" class="w-32 text-right bg-white px-1 border border-gray-400" value="0" oninput="calculateFooter()">
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-300 pt-1 font-bold">
                        <label class="w-32">Saldo Terhutang</label>
                        <input type="text" id="dispGrandTotal" class="w-32 text-right bg-gray-200 px-1 border border-gray-400 font-bold" readonly value="0">
                        <input type="hidden" name="grand_total_hidden" id="grandTotalHidden">
                        <input type="hidden" name="balance_hidden" id="balanceHidden">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function loadInvoices() {
            const supId = document.getElementById('supSelect').value;
            const invSelect = document.getElementById('invSelect');
            invSelect.innerHTML = '<option>Loading...</option>';
            
            if(supId) {
    fetch(`/pembelian/retur/get-invoices/${supId}`)
        .then(res => res.json())
        .then(data => {
            let opts = '<option value="">-- Pilih Faktur --</option>';
            
            // --- MULAI LOOPING ---
            data.forEach(i => {
                // Pindahkan logika tanggal KE DALAM SINI
                // Pastikan ada check jika tanggal null agar tidak error
                let dateOnly = i.purchase_date ? i.purchase_date.split('T')[0] : '-';
                
                // Tambahkan ke options
                opts += `<option value="${i.id}">${i.purchase_code} (${dateOnly})</option>`;
            });
            // --- SELESAI LOOPING ---

            invSelect.innerHTML = opts;
        })
        .catch(err => console.error(err)); // Tambahkan catch untuk cek error
}
        }

        function openSupModal() { document.getElementById('supplierModal').classList.remove('hidden'); }
    function closeSupModal() { document.getElementById('supplierModal').classList.add('hidden'); }
    
    function openInvModal() { 
        if(!document.getElementById('supId').value) return alert('Pilih supplier terlebih dahulu!');
        document.getElementById('invoiceModal').classList.remove('hidden'); 
    }
    function closeInvModal() { document.getElementById('invoiceModal').classList.add('hidden'); }

    // --- SELEKSI DATA ---
    // Ganti fungsi selectSupplier lama Anda dengan yang ini:
function selectSupplier(id, name, addr, city) {
    // Isi ID dan Nama ke input display
    document.getElementById('supId').value = id;
    document.getElementById('supDisplay').value = name;
    
    // LANGSUNG ISI ALAMAT DAN KOTA DI SINI
    document.getElementById('supAddr').value = addr || '-';
    document.getElementById('supCity').value = city || '-';
    
    // Reset data faktur jika sebelumnya sudah ada pilihan
    document.getElementById('invId').value = '';
    document.getElementById('invDisplay').value = '';
    document.getElementById('btnSearchInv').disabled = false; // Aktifkan tombol cari faktur
    document.getElementById('tableBody').innerHTML = ''; // Bersihkan baris item barang
    
    closeSupModal();
    
    // Tetap tarik data faktur di belakang layar agar saat klik "Cari Faktur" data sudah siap
    fetchInvoices(id); 
}

    function fetchInvoices(supId) {
        const tbody = document.getElementById('invTableBody');
        const loading = document.getElementById('invLoading');
        tbody.innerHTML = '';
        loading.classList.remove('hidden');

        fetch(`/pembelian/retur/get-invoices/${supId}`)
            .then(res => res.json())
            .then(data => {
                loading.classList.add('hidden');
                let rows = '';
                data.forEach(i => {
                    let dateOnly = i.purchase_date ? i.purchase_date.split('T')[0] : '-';
                    rows += `
                        <tr class="hover:bg-blue-50 cursor-pointer" onclick="selectInvoice('${i.id}', '${i.purchase_code}')">
                            <td class="p-2 border font-bold text-blue-700">${i.purchase_code}</td>
                            <td class="p-2 border">${dateOnly}</td>
                            <td class="p-2 border text-right">Rp ${fmt(i.grand_total)}</td>
                            <td class="p-2 border text-center">
                                <button type="button" class="bg-emerald-600 text-white px-2 py-0.5 rounded shadow">Pilih</button>
                            </td>
                        </tr>`;
                });
                tbody.innerHTML = rows || '<tr><td colspan="4" class="p-4 text-center">Tidak ditemukan faktur untuk supplier ini.</td></tr>';
            });
    }

    function selectInvoice(id, code) {
        document.getElementById('invId').value = id;
        document.getElementById('invDisplay').value = code;
        closeInvModal();
        loadDetails(id); // Fungsi loadDetails Anda tetap digunakan
    }

    // Fungsi pencarian teks (Universal)
    function filterTable(inputId, tableBodyId) {
        let input = document.getElementById(inputId).value.toLowerCase();
        let rows = document.querySelectorAll(`#${tableBodyId} tr`);
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
        });
    }

    // --- MODIFIKASI loadDetails (Tetap gunakan logika Anda, sesuaikan parameter) ---
    function loadDetails(invId) {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '<tr><td colspan="11" class="text-center italic text-blue-600 py-4">Memuat detail barang...</td></tr>';

        fetch(`/pembelian/retur/get-details/${invId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('supAddr').value = data.address;
                document.getElementById('supCity').value = data.city;
                document.getElementById('taxPct').value = data.tax_pct;
                document.getElementById('whSelect').value = data.warehouse_id;

                let rows = '';
                data.items.forEach((item, idx) => {
                    rows += `
                        <tr class="border-b hover:bg-blue-50">
                            <td class="p-1 border text-center">${idx+1}</td>
                            <td class="p-1 border">${item.code}</td>
                            <td class="p-1 border">${item.name}
                                <input type="hidden" name="products[${idx}][product_id]" value="${item.product_id}">
                                <input type="hidden" name="products[${idx}][detail_id]" value="${item.detail_id}">
                            </td>
                            <td class="p-1 border text-center">${item.unit} <input type="hidden" name="products[${idx}][unit]" value="${item.unit}"></td>
                            <td class="p-1 border text-right"><input type="text" name="products[${idx}][price]" class="w-full bg-transparent border-none text-right price-inp" value="${item.price}" readonly></td>
                            <td class="p-1 border bg-orange-100"><input type="number" name="products[${idx}][qty]" class="w-full bg-transparent border-none text-center font-bold qty-inp" value="0" max="${item.qty_bought}" oninput="calcRow(this)"><div class="text-[8px] text-gray-400 text-center">Max: ${item.qty_bought}</div></td>
                            <td class="p-1 border text-center"><input type="text" name="products[${idx}][disc_1]" class="w-full bg-transparent border-none text-center d1-inp" value="${item.disc_1}" readonly></td>
                            <td class="p-1 border text-center"><input type="text" name="products[${idx}][disc_2]" class="w-full bg-transparent border-none text-center d2-inp" value="${item.disc_2}" readonly></td>
                            <td class="p-1 border text-right"><input type="text" name="products[${idx}][disc_rp]" class="w-full bg-transparent border-none text-right drp-inp" value="${item.disc_rp}" readonly></td>
                            <td class="p-1 border text-right"><input type="text" class="w-full bg-transparent border-none text-right font-bold sub-disp" readonly value="0"><input type="hidden" name="products[${idx}][subtotal_row]" class="sub-val"></td>
                            <td class="p-1 border text-right font-semibold">${fmt(item.price)}</td>
                        </tr>`;
                });
                tbody.innerHTML = rows;
                calculateFooter();
            });
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

            if(total < 0) total = 0;

            tr.querySelector('.sub-disp').value = fmt(total);
            tr.querySelector('.sub-val').value = total;
            calculateFooter();
        }

        function calculateFooter() {
            let sub = 0;
            document.querySelectorAll('.sub-val').forEach(inp => sub += parseFloat(inp.value) || 0);

            const taxP = parseFloat(document.getElementById('taxPct').value) || 0;
            const taxVal = sub * (taxP / 100);
            const subPlusTax = sub + taxVal;

            const ship = parseFloat(document.getElementById('shipCost').value) || 0;
            const other = parseFloat(document.getElementById('otherCost').value) || 0;
            const paid = parseFloat(document.getElementById('cashRefund').value) || 0;

            const dPct = parseFloat(document.getElementById('discPct').value) || 0;
            let dAmt = parseFloat(document.getElementById('discAmt').value) || 0;
            if(dPct > 0) {
                dAmt = subPlusTax * (dPct / 100);
                document.getElementById('discAmt').value = dAmt;
            }

            const grand = subPlusTax + ship + other - dAmt;
            const balance = grand - paid;

            document.getElementById('dispSubtotal').value = fmt(sub);
            document.getElementById('subtotalHidden').value = sub;
            
            document.getElementById('subAfterTax').value = fmt(subPlusTax);
            document.getElementById('taxAmountHidden').value = taxVal;

            document.getElementById('dispGrandTotal').value = fmt(balance);
            document.getElementById('grandTotalHidden').value = grand;
            document.getElementById('balanceHidden').value = balance;
        }

        function fmt(n) {
            return new Intl.NumberFormat('id-ID').format(n);
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === "Enter" && event.target.tagName !== 'TEXTAREA') {
                event.preventDefault();
            }
        });
    </script>
    <div id="supplierModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 overflow-hidden text-xs">
        <div class="bg-blue-800 text-white p-3 flex justify-between items-center">
            <h3 class="font-bold"><i class="fas fa-truck mr-2"></i>Pilih Supplier</h3>
            <button type="button" onclick="closeSupModal()" class="text-white text-xl">&times;</button>
        </div>
        <div class="p-4">
            <input type="text" id="searchSup" placeholder="Cari Nama/Alamat/Kota..." class="w-full border p-2 mb-4 rounded" onkeyup="filterTable('searchSup', 'supTableBody')">
            <div class="max-h-72 overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="p-2 border">Nama Supplier</th>
                            <th class="p-2 border">Kota</th>
                            <th class="p-2 border">Alamat</th>
                            <th class="p-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="supTableBody">
                    @foreach($suppliers as $s)
<tr class="hover:bg-blue-50">
    <td class="p-2 border font-bold">{{ $s->name }}</td>
    <td class="p-2 border">{{ $s->city }}</td>
    <td class="p-2 border text-gray-500">{{ $s->address }}</td>
    <td class="p-2 border text-center">
        <button type="button" 
            onclick="selectSupplier('{{ $s->id }}', '{{ $s->name }}', '{{ $s->address }}', '{{ $s->city }}')" 
            class="bg-blue-600 text-white px-3 py-1 rounded">Pilih</button>
    </td>
</tr>
@endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="invoiceModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 overflow-hidden text-xs">
        <div class="bg-blue-800 text-white p-3 flex justify-between items-center">
            <h3 class="font-bold"><i class="fas fa-file-invoice mr-2"></i>Pilih Faktur Pembelian</h3>
            <button type="button" onclick="closeInvModal()" class="text-white text-xl">&times;</button>
        </div>
        <div class="p-4">
            <input type="text" id="searchInv" placeholder="Cari No. Faktur..." class="w-full border p-2 mb-4 rounded" onkeyup="filterTable('searchInv', 'invTableBody')">
            <div id="invLoading" class="hidden text-center py-4 text-blue-600 font-bold italic">Menarik data faktur...</div>
            <div class="max-h-72 overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="p-2 border">No. Faktur</th>
                            <th class="p-2 border">Tanggal</th>
                            <th class="p-2 border text-right">Total</th>
                            <th class="p-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="invTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-layout>