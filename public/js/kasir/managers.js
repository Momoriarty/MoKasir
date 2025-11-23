// managers.js - Bagian manajemen: update total, tambah/hapus barang, collect data, modal confirm/print

// UPDATE TOTAL
function updateTotal() {
    const rincianBox = document.getElementById("rincian-box");

    let subtotal = 0;
    const rows = tbody.querySelectorAll("tr:not(#emptyRow)");

    rows.forEach(tr => {
        const subtotalText = tr.children[4]?.innerText || "0";
        const total = parseInt(subtotalText.replace(/\D/g, "")) || 0;
        subtotal += total;
    });

    let pajak = 0;
    if (metode.value === "Qris" && subtotal > 0) {
        pajak = 1000;
    }

    let pajakRow = rincianBox.querySelector("#pajak-row");
    if (pajak > 0) {
        if (!pajakRow) {
            pajakRow = document.createElement("div");
            pajakRow.id = "pajak-row";
            pajakRow.classList.add("flex", "justify-between", "text-sm", "text-gray-700", "dark:text-gray-300");
            rincianBox.insertBefore(pajakRow, totalDisplayHeader.parentNode);
        }
        pajakRow.innerHTML = `<span>Biaya Admin QRIS:</span><span>${formatRupiah(pajak)}</span>`;
    } else {
        if (pajakRow) pajakRow.remove();
    }

    const totalBayar = subtotal + pajak;
    totalDisplayHeader.innerText = formatRupiah(totalBayar);

    let bayar = parseInt(bayarInput.value || 0);

    if (metode.value === "Qris" && subtotal > 0) {
        bayar = totalBayar;
        bayarInput.value = bayar;
        bayarInput.disabled = true;
    } else if (metode.value === "Tunai") {
        bayarInput.disabled = false;
    }

    const kembali = bayar - totalBayar;
    kembaliDisplayHeader.innerText = formatRupiah(kembali >= 0 ? kembali : 0);

    const isBayarCukup = bayar >= totalBayar;
    btnSimpan.disabled = subtotal === 0 || !isBayarCukup;

    if (metode.value === "Qris" && subtotal > 0) {
        generateQRIS(totalBayar); // Dari payment-qris.js
    } else {
        clearQRCode(); // Dari payment-qris.js
        qrisBox.classList.add("hidden");
    }
}

// TAMBAH BARANG STOK
function addBarangStok() {
    if (!selectBarang.value) {
        showToast('Peringatan', 'Silakan pilih barang terlebih dahulu', 'warning');
        return;
    }

    const opt = selectBarang.selectedOptions[0];
    const nama = opt.dataset.nama;
    const jumlahKardus = parseInt(jumlahKardusInput.value) || 0;
    const jumlahEcer = parseInt(jumlahEcerInput.value) || 0;

    if (jumlahKardus <= 0 && jumlahEcer <= 0) {
        showToast('Peringatan', 'Masukkan jumlah minimal 1', 'warning');
        return;
    }

    let stokKardus = parseInt(opt.dataset.stokKardus || 0);
    let stokEcer = parseInt(opt.dataset.stokEcer || 0);

    if (jumlahKardus > stokKardus) {
        showToast('Error', 'Jumlah kardus melebihi stok!', 'error');
        return;
    }
    if (jumlahEcer > stokEcer) {
        showToast('Error', 'Jumlah ecer melebihi stok!', 'error');
        return;
    }

    const hargaK = parseInt(opt.dataset.hargaKardus) || 0;
    const hargaE = parseInt(opt.dataset.hargaEcer) || 0;
    const total = jumlahKardus * hargaK + jumlahEcer * hargaE;

    emptyRow.classList.add('hidden');

    const tr = document.createElement("tr");
    tr.className = "hover:bg-gray-200 dark:hover:bg-gray-700 transition";
    tr.dataset.type = "barang";
    tr.innerHTML = `
        <td class="px-6 py-3" data-id-barang="${selectBarang.value}">${nama}</td>
        <td class="px-6 py-3">
            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded text-xs font-semibold">
                Stok
            </span>
        </td>
        <td class="px-6 py-3 text-center">
            <div class="text-xs">
                ${jumlahKardus > 0 ? `${jumlahKardus} Kardus` : ''}
                ${jumlahKardus > 0 && jumlahEcer > 0 ? '<br>' : ''}
                ${jumlahEcer > 0 ? `${jumlahEcer} Ecer` : ''}
            </div>
            <input type="hidden" class="kardus-count" value="${jumlahKardus}">
            <input type="hidden" class="ecer-count" value="${jumlahEcer}">
            <input type="hidden" class="harga-kardus" value="${hargaK}">
            <input type="hidden" class="harga-ecer" value="${hargaE}">
        </td>
        <td class="px-6 py-3">
            <div class="text-xs">
                ${jumlahKardus > 0 ? `${formatRupiah(hargaK)}` : ''}
                ${jumlahKardus > 0 && jumlahEcer > 0 ? '<br>' : ''}
                ${jumlahEcer > 0 ? `${formatRupiah(hargaE)}` : ''}
            </div>
        </td>
        <td class="px-6 py-3 font-semibold">${formatRupiah(total)}</td>
        <td class="px-6 py-3 text-center">
            <button class="hapus bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition text-sm">
                🗑️ Hapus
            </button>
        </td>
    `;
    tbody.appendChild(tr);

    stokKardus -= jumlahKardus;
    stokEcer -= jumlahEcer;
    opt.dataset.stokKardus = stokKardus;
    opt.dataset.stokEcer = stokEcer;
    updateOptionText(opt, stokKardus, stokEcer);

    updateTotal();

    selectBarang.value = "";
    jumlahKardusInput.value = "";
    jumlahEcerInput.value = "";
    maxKardus.textContent = '';
    maxEcer.textContent = '';
    jumlahKardusInput.disabled = true;
    jumlahEcerInput.disabled = true;

    showToast('Berhasil', `${nama} ditambahkan ke keranjang`, 'success');
    selectBarang.focus();
}

// TAMBAH BARANG PENITIPAN
function addBarangPenitipan() {
    if (!selectPenitipan.value) {
        showToast('Peringatan', 'Silakan pilih barang penitipan terlebih dahulu', 'warning');
        return;
    }

    const opt = selectPenitipan.selectedOptions[0];
    const nama = opt.dataset.nama;
    const penitip = opt.dataset.penitip;
    const jumlah = parseInt(jumlahPenitipanInput.value) || 0;
    let stok = parseInt(opt.dataset.stok || 0);

    if (jumlah <= 0) {
        showToast('Peringatan', 'Masukkan jumlah minimal 1', 'warning');
        return;
    }

    if (jumlah > stok) {
        showToast('Error', 'Jumlah melebihi stok!', 'error');
        return;
    }

    const harga = parseInt(opt.dataset.harga) || 0;
    const total = jumlah * harga;

    emptyRow.classList.add('hidden');

    const tr = document.createElement("tr");
    tr.className = "hover:bg-gray-200 dark:hover:bg-gray-700 transition";
    tr.dataset.type = "penitipan";
    tr.innerHTML = `
        <td class="px-6 py-3" data-penitipan-id="${selectPenitipan.value}">
            ${nama}
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Penitip: ${penitip}</div>
        </td>
        <td class="px-6 py-3">
            <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded text-xs font-semibold">
                Titipan
            </span>
        </td>
        <td class="px-6 py-3 text-center">
            ${jumlah} pcs
            <input type="hidden" class="penitipan-jumlah" value="${jumlah}">
            <input type="hidden" class="penitipan-harga" value="${harga}">
        </td>
        <td class="px-6 py-3">${formatRupiah(harga)}</td>
        <td class="px-6 py-3 font-semibold">${formatRupiah(total)}</td>
        <td class="px-6 py-3 text-center">
            <button class="hapus bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition text-sm">
                🗑️ Hapus
            </button>
        </td>
    `;
    tbody.appendChild(tr);

    stok -= jumlah;
    opt.dataset.stok = stok;
    opt.text = `${nama} - ${penitip} (Sisa: ${stok})`;
    if (stok === 0) opt.disabled = true;

    updateTotal();

    selectPenitipan.value = "";
    jumlahPenitipanInput.value = "";
    hargaPenitipan.value = "";
    maxPenitipan.textContent = '';
    jumlahPenitipanInput.disabled = true;

    showToast('Berhasil', `${nama} (titipan) ditambahkan ke keranjang`, 'success');
    selectPenitipan.focus();
}

// HAPUS BARANG
function removeBarang(e) {
    const btn = e.target.classList.contains("hapus") ? e.target : e.target.closest('.hapus');
    const tr = btn.closest("tr");
    const type = tr.dataset.type;

    if (type === "barang") {
        const idBarang = tr.children[0].dataset.idBarang;
        const namaBarang = tr.children[0].innerText;
        const jumlahKardus = parseInt(tr.querySelector('.kardus-count').value) || 0;
        const jumlahEcer = parseInt(tr.querySelector('.ecer-count').value) || 0;

        const opt = Array.from(selectBarang.options).find(o => o.value === idBarang);
        if (opt) {
            let stokKardus = parseInt(opt.dataset.stokKardus || 0) + jumlahKardus;
            let stokEcer = parseInt(opt.dataset.stokEcer || 0) + jumlahEcer;
            opt.dataset.stokKardus = stokKardus;
            opt.dataset.stokEcer = stokEcer;
            updateOptionText(opt, stokKardus, stokEcer);
        }

        showToast('Dihapus', `${namaBarang} dihapus dari keranjang`, 'info');
    } else if (type === "penitipan") {
        const penitipanId = tr.children[0].dataset.penitipanId;
        const namaBarang = tr.children[0].innerText.split('\n')[0].trim();
        const jumlah = parseInt(tr.querySelector('.penitipan-jumlah').value) || 0;

        const opt = Array.from(selectPenitipan.options).find(o => o.value === penitipanId);
        if (opt) {
            let stok = parseInt(opt.dataset.stok || 0) + jumlah;
            opt.dataset.stok = stok;
            const nama = opt.dataset.nama;
            const penitip = opt.dataset.penitip;
            opt.text = `${nama} - ${penitip} (Sisa: ${stok})`;
            opt.disabled = false;
        }

        showToast('Dihapus', `${namaBarang} (titipan) dihapus dari keranjang`, 'info');
    }

    tr.remove();

    const remainingRows = tbody.querySelectorAll("tr:not(#emptyRow)");
    if (remainingRows.length === 0) {
        emptyRow.classList.remove('hidden');
    }

    updateTotal();
}

// COLLECT DATA
function collectBarangData() {
    let barangStok = [];
    let barangPenitipan = [];

    tbody.querySelectorAll("tr:not(#emptyRow)").forEach(tr => {
        const type = tr.dataset.type;

        if (type === "barang") {
            const cells = tr.children;
            barangStok.push({
                id_barang: cells[0].dataset.idBarang,
                jumlah_kardus: parseInt(tr.querySelector('.kardus-count').value) || 0,
                harga_kardus: parseInt(tr.querySelector('.harga-kardus').value) || 0,
                jumlah_ecer: parseInt(tr.querySelector('.ecer-count').value) || 0,
                harga_ecer: parseInt(tr.querySelector('.harga-ecer').value) || 0,
                subtotal: parseInt(cells[4].innerText.replace(/\D/g, "")) || 0
            });
        } else if (type === "penitipan") {
            const cells = tr.children;
            barangPenitipan.push({
                id_penitipan_detail: cells[0].dataset.penitipanId,
                jumlah: parseInt(tr.querySelector('.penitipan-jumlah').value) || 0,
                harga_jual: parseInt(tr.querySelector('.penitipan-harga').value) || 0,
                subtotal: parseInt(cells[4].innerText.replace(/\D/g, "")) || 0
            });
        }
    });

    return { barangStok, barangPenitipan };
}

function getCurrentSubtotal() {
    let subtotal = 0;
    tbody.querySelectorAll("tr:not(#emptyRow)").forEach(tr => {
        const cells = tr.children;
        if (cells[4]) {
            const cellVal = cells[4].innerText.replace(/\D/g, "");
            subtotal += parseInt(cellVal) || 0;
        }
    });
    return subtotal;
}

// CONFIRM MODAL
function showConfirmModal() {
    const subtotal = getCurrentSubtotal();
    if (subtotal <= 0) {
        showToast('Peringatan', 'Keranjang masih kosong', 'warning');
        return;
    }

    const totalBayar = parseInt(totalDisplayHeader.innerText.replace(/\D/g, "")) || 0;
    const bayar = parseInt(bayarInput.value || 0);
    const kembali = bayar - totalBayar;

    if (bayar < totalBayar) {
        showToast('Error', 'Jumlah bayar kurang dari total', 'error');
        return;
    }

    const { barangStok, barangPenitipan } = collectBarangData();
    const itemCount = barangStok.length + barangPenitipan.length;

    const confirmContent = document.getElementById('confirmContent');
    confirmContent.innerHTML = `
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="font-semibold">Total Item:</span>
                <span>${itemCount} barang (${barangStok.length} stok, ${barangPenitipan.length} titipan)</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Total Bayar:</span>
                <span>${formatRupiah(totalBayar)}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Uang Diterima:</span>
                <span>${formatRupiah(bayar)}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Kembalian:</span>
                <span class="text-green-600 font-bold">${formatRupiah(kembali)}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Metode:</span>
                <span class="font-semibold">${metode.value === 'Tunai' ? '💵 Tunai' : '📱 QRIS'}</span>
            </div>
        </div>
    `;

    transactionData = {
        total_harga: subtotal,
        total_bayar: bayar,
        metode: metode.value,
        barang: barangStok,
        penitipan: barangPenitipan
    };

    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

// CONFIRM TRANSACTION
function confirmTransaction() {
    if (!transactionData) {
        showToast('Error', 'Data transaksi tidak valid', 'error');
        closeConfirmModal();
        return;
    }

    showLoading();

    fetch("{{ route('kasir.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify(transactionData)
    })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Server error');
            }
            return data;
        })
        .then(res => {
            hideLoading();
            closeConfirmModal();

            if (res.success) {
                showPrintModal(res.transaksi);
            } else {
                showToast('Error', res.message || 'Gagal menyimpan transaksi', 'error');
            }
        })
        .catch(err => {
            hideLoading();
            closeConfirmModal();
            showToast('Error', 'Terjadi kesalahan: ' + err.message, 'error');
        });
}

// PRINT MODAL
function showPrintModal(transaksi) {
    document.getElementById('transaksiId').textContent = transaksi.id_transaksi || 'N/A';

    const strukContent = document.getElementById('strukContent');
    const now = new Date();
    const tanggal = now.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
    const waktu = now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });

    let itemsHtml = '';
    transaksi.detail_transaksi.forEach(item => {
        itemsHtml += `
            <div class="flex justify-between">
                <div>
                    <span class="font-semibold">${item.nama_barang}</span><br>
                    <span class="text-xs text-gray-600 dark:text-gray-400">
                        ${item.jumlah_kardus > 0 ? item.jumlah_kardus + ' Kardus ' : ''}
                        ${item.jumlah_ecer > 0 ? item.jumlah_ecer + ' Ecer' : ''}
                    </span>
                </div>
                <div class="text-right">
                    <span>${formatRupiah(item.subtotal)}</span>
                </div>
            </div>
        `;
    });

    strukContent.innerHTML = `
        <div class="text-center mb-4">
            <h2 class="text-lg font-bold">Struk Pembayaran</h2> 
            <div class="text-sm text-gray-600 dark:text-gray-400">${tanggal} ${waktu}</div>
        </div>
        <div class="space-y-2">
            ${itemsHtml}
            <hr class="my-2 border-gray-300 dark:border-gray-600">
            <div class="flex justify-between font-bold">
                <span>Total Bayar:</span>
                <span></span>${formatRupiah(transaksi.total_bayar)}</span>
            </div>
            <div class="flex justify-between">
                <span>Metode Pembayaran:</span>
                <span>${transaksi.metode === 'Tunai' ? '💵 Tunai' : '📱 QRIS'}</span>
            </div>
        </div>
    `;

    document.getElementById('printModal').classList.remove('hidden');
}

function closePrintModal() {
    document.getElementById('printModal').classList.add('hidden');
}

// TAB SWITCHING
function switchTab(tab) {
    document.getElementById('tabBarang').classList.toggle('border-blue-500', tab === 'barang');
    document.getElementById('tabBarang').classList.toggle('text-blue-600', tab === 'barang');
    document.getElementById('tabBarang').classList.toggle('dark:text-blue-400', tab === 'barang');
    document.getElementById('tabBarang').classList.toggle('border-transparent', tab !== 'barang');
    document.getElementById('tabBarang').classList.toggle('text-gray-600', tab !== 'barang');

    document.getElementById('tabPenitipan').classList.toggle('border-blue-500', tab === 'penitipan');
    document.getElementById('tabPenitipan').classList.toggle('text-blue-600', tab === 'penitipan');
    document.getElementById('tabPenitipan').classList.toggle('dark:text-blue-400', tab === 'penitipan');
    document.getElementById('tabPenitipan').classList.toggle('border-transparent', tab !== 'penitipan');
    document.getElementById('tabPenitipan').classList.toggle('text-gray-600', tab !== 'penitipan');

    document.getElementById('formBarang').classList.toggle('hidden', tab !== 'barang');
    document.getElementById('formPenitipan').classList.toggle('hidden', tab !== 'penitipan');
}

// TOAST
function showToast(title, message, type = 'success') {
    const toast = document.getElementById('toast');
    const icon = document.getElementById('toast-icon');
    const titleEl = document.getElementById('toast-title');
    const messageEl = document.getElementById('toast-message');

    const icons = { success: '✓', error: '✕', warning: '⚠', info: 'ℹ' };
    const colors = { success: 'border-green-500', error: 'border-red-500', warning: 'border-yellow-500', info: 'border-blue-500' };

    icon.textContent = icons[type] || icons.success;
    titleEl.textContent = title;
    messageEl.textContent = message;

    toast.querySelector('div').className = `bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border-l-4 ${colors[type]} max-w-md`;
    toast.classList.remove('hidden');

    setTimeout(() => hideToast(), 5000);
}

function hideToast() {
    document.getElementById('toast').classList.add('hidden');
}

function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}   