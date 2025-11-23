// main.js — cleaned, safe, and compatible with managers.js & payment-qris.js

// -----------------------------
// Utilities (safe to run before DOM ready)
// -----------------------------

// ========================================
// KasirState — FIX to support payment-qris.js
// ========================================
const KasirState = {
    elements: {
        metode: document.getElementById("metodePembayaran"),
        bayarInput: document.getElementById("bayar"),
        totalDisplay: document.getElementById("totalBayar"),
        kembaliDisplay: document.getElementById("kembalian"),
        btnSimpan: document.getElementById("btnBayar"),
        qrisBox: document.getElementById("qrisBox"),
        qrCanvas: document.getElementById("qrCanvas"),
        qrLoading: document.getElementById("qrLoading"),

        // Untuk mapping barang & penitipan (dipakai struk)
        selectBarang: document.getElementById("selectBarang"),
        selectPenitipan: document.getElementById("selectPenitipan"),
    },

    transactionData: null
};


function formatRupiah(angka) {
    if (angka === null || angka === undefined || angka === '') return "Rp 0";
    const n = Number(String(angka).replace(/\D/g, "")) || 0;
    return "Rp " + n.toLocaleString("id-ID");
}

function updateOptionText(opt, stokKardus, stokEcer) {
    if (!opt) return;
    const nama = opt.dataset.nama || opt.text || '';
    opt.text = `${nama} - Stok Kardus: ${stokKardus}, Stok Ecer: ${stokEcer}`;
    opt.disabled = (parseInt(stokKardus || 0) === 0) && (parseInt(stokEcer || 0) === 0);
}

// -----------------------------
// Declare globals that managers.js expects (will be assigned on DOMContentLoaded)
// -----------------------------
let tbody, emptyRow, totalDisplayHeader, bayarInput, kembaliDisplayHeader;
let metode, qrisBox, qrCanvas, qrLoading;
let btnTambah, selectBarang, jumlahKardusInput, jumlahEcerInput, btnSimpan;
let maxKardus, maxEcer, selectPenitipan, jumlahPenitipanInput, hargaPenitipan, maxPenitipan, btnTambahPenitipan;

// Keep transactionData & currentTab as in your original file
let transactionData = null;
let currentTab = 'barang';

// -----------------------------
// Safe wrappers for QRIS functions (so old calls clearQRCode()/generateQRIS() still work)
// -----------------------------
function clearQRCode() {
    // Prefer QRISManager.clear() if exists
    try {
        if (typeof QRISManager !== 'undefined' && typeof QRISManager.clear === 'function') {
            QRISManager.clear();
            return;
        }
    } catch (e) {
        // ignore and fallback
    }

    // Fallback: try to clear canvas elements if present
    try {
        if (qrCanvas && qrCanvas.getContext) {
            const ctx = qrCanvas.getContext('2d');
            ctx.clearRect(0, 0, qrCanvas.width, qrCanvas.height);
        }
        if (qrisBox) qrisBox.classList.add('hidden');
    } catch (e) {
        // silent
        console.warn('clearQRCode fallback failed', e);
    }
}

function generateQRIS(total) {
    try {
        if (typeof QRISManager !== 'undefined' && typeof QRISManager.generate === 'function') {
            QRISManager.generate(total);
            return;
        }
    } catch (e) {
        console.warn('QRISManager.generate not available', e);
    }

    // fallback: show qrisBox hidden state (no real QR generation)
    if (qrisBox) qrisBox.classList.remove('hidden');
    if (qrLoading) qrLoading.classList.add('hidden');
    console.warn('generateQRIS fallback used — no QR generated');
}

// -----------------------------
// DOM ready: wire everything up
// -----------------------------
document.addEventListener('DOMContentLoaded', () => {
    // -----------------------------
    // Assign DOM elements to globals
    // -----------------------------
    tbody = document.getElementById("tabel-barang");
    emptyRow = document.getElementById("emptyRow");
    totalDisplayHeader = document.getElementById("total-display");
    bayarInput = document.getElementById("bayar-input");
    kembaliDisplayHeader = document.getElementById("kembali-display");
    metode = document.getElementById("metode");
    qrisBox = document.getElementById("qrisBox");
    qrCanvas = document.getElementById("qrCanvas");
    qrLoading = document.getElementById("qrLoading");
    btnTambah = document.getElementById("btn-tambah");
    selectBarang = document.getElementById("selectBarang");
    jumlahKardusInput = document.getElementById("jumlahKardus");
    jumlahEcerInput = document.getElementById("jumlahEcer");
    btnSimpan = document.getElementById("btn-simpan");
    maxKardus = document.getElementById("maxKardus");
    maxEcer = document.getElementById("maxEcer");
    selectPenitipan = document.getElementById("selectPenitipan");
    jumlahPenitipanInput = document.getElementById("jumlahPenitipan");
    hargaPenitipan = document.getElementById("hargaPenitipan");
    maxPenitipan = document.getElementById("maxPenitipan");
    btnTambahPenitipan = document.getElementById("btn-tambah-penitipan");

    // -----------------------------
    // Safe-check: ensure required elements exist before adding listeners
    // (prevents TypeError if some element is missing in some template)
    // -----------------------------
    // TAB SWITCHING
    const tabBarangEl = document.getElementById('tabBarang');
    const tabPenitipanEl = document.getElementById('tabPenitipan');

    if (tabBarangEl) tabBarangEl.addEventListener('click', () => switchTab('barang'));
    if (tabPenitipanEl) tabPenitipanEl.addEventListener('click', () => switchTab('penitipan'));

    // KEYBOARD SHORTCUTS
    document.addEventListener('keydown', (e) => {
        if (e.key === 'F2') {
            e.preventDefault();
            switchTab('barang');
            if (selectBarang) selectBarang.focus();
        }
        if (e.key === 'F3') {
            e.preventDefault();
            switchTab('penitipan');
            if (selectPenitipan) selectPenitipan.focus();
        }
        if (e.key === 'F9') {
            e.preventDefault();
            if (btnSimpan && !btnSimpan.disabled) {
                if (typeof showConfirmModal === 'function') showConfirmModal();
            }
        }
        if (e.key === 'Escape') {
            if (typeof closeConfirmModal === 'function') closeConfirmModal();
            if (typeof closePrintModal === 'function') closePrintModal();
        }
    });

    // SELECT BARANG change
    if (selectBarang) {
        selectBarang.addEventListener("change", () => {
            const opt = selectBarang.selectedOptions[0];
            if (!opt || !opt.value) {
                if (maxKardus) maxKardus.textContent = '';
                if (maxEcer) maxEcer.textContent = '';
                if (jumlahKardusInput) { jumlahKardusInput.disabled = true; jumlahKardusInput.value = ""; }
                if (jumlahEcerInput) { jumlahEcerInput.disabled = true; jumlahEcerInput.value = ""; }
                return;
            }

            const stokKardus = parseInt(opt.dataset.stokKardus || 0);
            const stokEcer = parseInt(opt.dataset.stokEcer || 0);

            if (jumlahKardusInput) jumlahKardusInput.disabled = stokKardus === 0;
            if (jumlahEcerInput) jumlahEcerInput.disabled = stokEcer === 0;

            if (maxKardus) maxKardus.textContent = stokKardus > 0 ? `Maksimal: ${stokKardus} kardus` : 'Stok kardus habis';
            if (maxEcer) maxEcer.textContent = stokEcer > 0 ? `Maksimal: ${stokEcer} ecer` : 'Stok ecer habis';

            if (stokKardus === 0 && jumlahKardusInput) jumlahKardusInput.value = "";
            if (stokEcer === 0 && jumlahEcerInput) jumlahEcerInput.value = "";
        });
    }

    // SELECT PENITIPAN change
    if (selectPenitipan) {
        selectPenitipan.addEventListener("change", () => {
            const opt = selectPenitipan.selectedOptions[0];
            if (!opt || !opt.value) {
                if (hargaPenitipan) hargaPenitipan.value = '';
                if (maxPenitipan) maxPenitipan.textContent = '';
                if (jumlahPenitipanInput) { jumlahPenitipanInput.disabled = true; jumlahPenitipanInput.value = ""; }
                return;
            }

            const harga = parseInt(opt.dataset.harga || 0);
            const stok = parseInt(opt.dataset.stok || 0);

            if (hargaPenitipan) hargaPenitipan.value = formatRupiah(harga);
            if (jumlahPenitipanInput) { jumlahPenitipanInput.disabled = false; jumlahPenitipanInput.value = ""; }
            if (maxPenitipan) maxPenitipan.textContent = `Maksimal: ${stok} pcs`;
        });
    }

    // METODE change (uses payment-qris if present)
    if (metode) {
        metode.addEventListener("change", () => {
            // getCurrentSubtotal should come from managers.js — only call if exists
            let subtotal = 0;
            if (typeof getCurrentSubtotal === 'function') subtotal = getCurrentSubtotal();

            if (metode.value === "Qris") {
                const totalWithAdmin = subtotal + 1000;
                if (bayarInput) { bayarInput.value = totalWithAdmin; bayarInput.disabled = true; }

                if (subtotal > 0) {
                    generateQRIS(totalWithAdmin);
                } else if (qrisBox) {
                    qrisBox.classList.add("hidden");
                }
            } else {
                if (bayarInput) bayarInput.disabled = false;
                if (qrisBox) qrisBox.classList.add("hidden");
                clearQRCode();
            }

            if (typeof updateTotal === 'function') updateTotal();
        });
    }

    // TAMBAH BARANG stok
    if (btnTambah) {
        btnTambah.addEventListener("click", () => {
            if (typeof addBarangStok === 'function') addBarangStok();
            else console.warn('addBarangStok not defined');
        });
    }

    // TAMBAH BARANG PENITIPAN
    if (btnTambahPenitipan) {
        btnTambahPenitipan.addEventListener("click", () => {
            if (typeof addBarangPenitipan === 'function') addBarangPenitipan();
            else console.warn('addBarangPenitipan not defined');
        });
    }

    // HAPUS BARANG via delegation
    if (tbody) {
        tbody.addEventListener("click", e => {
            if (e.target.classList.contains("hapus") || e.target.closest('.hapus')) {
                if (typeof removeBarang === 'function') removeBarang(e);
            }
        });
    }

    // bayar input realtime: call updateTotal if present, else attempt to call PaymentManager.updateTotal
    if (bayarInput) {
        bayarInput.addEventListener("input", () => {
            if (typeof updateTotal === 'function') updateTotal();
            else if (typeof PaymentManager !== 'undefined' && typeof PaymentManager.updateTotal === 'function') PaymentManager.updateTotal();
        });
    }

    // tombol simpan
    if (btnSimpan) {
        btnSimpan.addEventListener("click", () => {
            if (typeof showConfirmModal === 'function') showConfirmModal();
            else if (typeof TransactionManager !== 'undefined' && typeof TransactionManager.showConfirmModal === 'function') TransactionManager.showConfirmModal();
            else console.warn('showConfirmModal not found');
        });
    }

    // INITIALIZATION: set displays and defaults
    try {
        if (totalDisplayHeader) totalDisplayHeader.innerText = formatRupiah(0);
        if (kembaliDisplayHeader) kembaliDisplayHeader.innerText = formatRupiah(0);
        if (bayarInput) bayarInput.disabled = false;
        clearQRCode();
        if (btnSimpan) btnSimpan.disabled = true;

        if (jumlahKardusInput) jumlahKardusInput.disabled = true;
        if (jumlahEcerInput) jumlahEcerInput.disabled = true;
        if (jumlahPenitipanInput) jumlahPenitipanInput.disabled = true;
    } catch (e) {
        console.warn('initialization warning', e);
    }

    // Expose some utilities/globals for other modules if they check window
    window.formatRupiah = formatRupiah;
    window.updateOptionText = updateOptionText;
    window.clearQRCode = clearQRCode;
    window.generateQRIS = generateQRIS;
    window.transactionData = transactionData;
    window.currentTab = currentTab;
});
