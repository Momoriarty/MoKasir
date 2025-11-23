// ========================================
// FILE: public/js/kasir/payment-qris.js
// Tambahkan setelah managers.js di kasir.blade.php
// ========================================

// ========================================
// Payment Manager
// ========================================
const PaymentManager = {
    updateTotal() {
        const e = KasirState.elements;
        const rincianBox = document.getElementById("rincian-box");
        
        let subtotal = CartManager.getSubtotal();
        let pajak = 0;
        
        if (e.metode.value === "Qris" && subtotal > 0) {
            pajak = 1000;
        }

        // Update/Remove pajak row
        let pajakRow = rincianBox.querySelector("#pajak-row");
        if (pajak > 0) {
            if (!pajakRow) {
                pajakRow = document.createElement("div");
                pajakRow.id = "pajak-row";
                pajakRow.classList.add("flex", "justify-between", "text-sm", "text-gray-700", "dark:text-gray-300");
                rincianBox.insertBefore(pajakRow, e.totalDisplay.parentNode);
            }
            pajakRow.innerHTML = `<span>Biaya Admin QRIS:</span><span>${Utils.formatRupiah(pajak)}</span>`;
        } else {
            if (pajakRow) pajakRow.remove();
        }

        const totalBayar = subtotal + pajak;
        e.totalDisplay.innerText = Utils.formatRupiah(totalBayar);

        let bayar = parseInt(e.bayarInput.value || 0);
        
        if (e.metode.value === "Qris" && subtotal > 0) {
            bayar = totalBayar;
            e.bayarInput.value = bayar;
            e.bayarInput.disabled = true;
        } else if (e.metode.value === "Tunai") {
            e.bayarInput.disabled = false;
        }

        const kembali = bayar - totalBayar;
        e.kembaliDisplay.innerText = Utils.formatRupiah(kembali >= 0 ? kembali : 0);

        const isBayarCukup = bayar >= totalBayar;
        e.btnSimpan.disabled = subtotal === 0 || !isBayarCukup;

        if (e.metode.value === "Qris" && subtotal > 0) {
            QRISManager.generate(totalBayar);
        } else {
            QRISManager.clear();
            e.qrisBox.classList.add("hidden");
        }
    },
    
    onMetodeChange() {
        const e = KasirState.elements;
        const subtotal = CartManager.getSubtotal();
        
        if (e.metode.value === "Qris") {
            const totalWithAdmin = subtotal + 1000;
            e.bayarInput.value = totalWithAdmin;
            e.bayarInput.disabled = true;
            
            if (subtotal > 0) {
                QRISManager.generate(totalWithAdmin);
            } else {
                e.qrisBox.classList.add("hidden");
            }
        } else {
            e.bayarInput.disabled = false;
            e.qrisBox.classList.add("hidden");
            QRISManager.clear();
        }
        
        PaymentManager.updateTotal();
    }
};

// ========================================
// QRIS Manager
// ========================================
const QRISManager = {
    crc16_ccitt(str) {
        let crc = 0xFFFF;
        for (let i = 0; i < str.length; i++) {
            crc ^= str.charCodeAt(i) << 8;
            for (let j = 0; j < 8; j++) {
                crc = (crc & 0x8000) ? ((crc << 1) ^ 0x1021) : (crc << 1);
                crc &= 0xFFFF;
            }
        }
        return crc.toString(16).toUpperCase().padStart(4, '0');
    },

    removeOldAmountAndCRC(payload) {
        payload = payload.replace(/54\d{2}\d{1,12}/, "");
        payload = payload.replace(/6304[0-9A-Fa-f]{4}$/, "");
        return payload;
    },

    clear() {
        try {
            const ctx = KasirState.elements.qrCanvas.getContext('2d');
            ctx.clearRect(0, 0, KasirState.elements.qrCanvas.width, KasirState.elements.qrCanvas.height);
        } catch (e) {
            console.error('Error clearing QR:', e);
        }
    },

    generate(totalBayar) {
        const e = KasirState.elements;
        e.qrisBox.classList.remove("hidden");
        e.qrLoading.classList.remove("hidden");
        this.clear();

        let img = new Image();
        img.crossOrigin = "anonymous";
        img.src = window.kasirConfig.qrisImage;

        img.onload = () => {
            try {
                let tmp = document.createElement("canvas");
                let ctxTmp = tmp.getContext("2d");
                tmp.width = img.width;
                tmp.height = img.height;
                ctxTmp.drawImage(img, 0, 0);

                let raw;
                try {
                    raw = ctxTmp.getImageData(0, 0, tmp.width, tmp.height);
                } catch (err) {
                    e.qrLoading.classList.add("hidden");
                    Toast.show('Error', 'Gagal membaca QR statis (CORS)', 'error');
                    return;
                }

                let qr = jsQR(raw.data, tmp.width, tmp.height);
                if (!qr) {
                    e.qrLoading.classList.add("hidden");
                    Toast.show('Error', 'QR code tidak valid', 'error');
                    return;
                }

                let payload = qr.data;
                payload = this.removeOldAmountAndCRC(payload);

                let nominal = String(totalBayar).replace(/\D/g, "") || "0";
                let length = String(nominal.length).padStart(2, "0");
                let tag54 = `54${length}${nominal}`;

                let payloadNoCRC = payload + tag54 + "6304";
                let crc = this.crc16_ccitt(payloadNoCRC);
                let finalPayload = payloadNoCRC + crc;

                this.clear();
                QRCode.toCanvas(e.qrCanvas, finalPayload, { width: 260 })
                    .then(() => {
                        e.qrLoading.classList.add("hidden");
                    })
                    .catch(err => {
                        e.qrLoading.classList.add("hidden");
                        Toast.show('Error', 'Gagal generate QR dinamis', 'error');
                    });

            } catch (err) {
                e.qrLoading.classList.add("hidden");
                Toast.show('Error', 'Error saat memproses QR: ' + err.message, 'error');
            }
        };

        img.onerror = () => {
            e.qrLoading.classList.add("hidden");
            Toast.show('Error', 'Gagal memuat qris.jpeg', 'error');
        };
    }
};

// ========================================
// Transaction Manager
// ========================================
const TransactionManager = {
    showConfirmModal() {
        const subtotal = CartManager.getSubtotal();
        if (subtotal <= 0) {
            Toast.show('Peringatan', 'Keranjang masih kosong', 'warning');
            return;
        }

        const e = KasirState.elements;
        const totalBayar = parseInt(e.totalDisplay.innerText.replace(/\D/g, "")) || 0;
        const bayar = parseInt(e.bayarInput.value || 0);
        const kembali = bayar - totalBayar;

        if (bayar < totalBayar) {
            Toast.show('Error', 'Jumlah bayar kurang dari total', 'error');
            return;
        }

        const { barangStok, barangPenitipan } = CartManager.collectData();
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
                    <span>${Utils.formatRupiah(totalBayar)}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Uang Diterima:</span>
                    <span>${Utils.formatRupiah(bayar)}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Kembalian:</span>
                    <span class="text-green-600 font-bold">${Utils.formatRupiah(kembali)}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Metode:</span>
                    <span class="font-semibold">${e.metode.value === 'Tunai' ? '💵 Tunai' : '📱 QRIS'}</span>
                </div>
            </div>
        `;

        KasirState.transactionData = {
            total_harga: subtotal,
            total_bayar: bayar,
            metode: e.metode.value,
            barang: barangStok,
            penitipan: barangPenitipan
        };

        document.getElementById('confirmModal').classList.remove('hidden');
    },

    closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    },

    confirmTransaction() {
        if (!KasirState.transactionData) {
            Toast.show('Error', 'Data transaksi tidak valid', 'error');
            this.closeConfirmModal();
            return;
        }

        Loading.show();

        fetch(window.kasirConfig.storeUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.kasirConfig.csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify(KasirState.transactionData)
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Server error');
            }
            return data;
        })
        .then(res => {
            Loading.hide();
            this.closeConfirmModal();
            
            if (res.success) {
                this.showPrintModal(res.transaksi);
            } else {
                Toast.show('Error', res.message || 'Gagal menyimpan transaksi', 'error');
            }
        })
        .catch(err => {
            Loading.hide();
            this.closeConfirmModal();
            Toast.show('Error', 'Terjadi kesalahan: ' + err.message, 'error');
        });
    },

    showPrintModal(transaksi) {
        document.getElementById('transaksiId').textContent = transaksi.id_transaksi || 'N/A';
        
        const strukContent = document.getElementById('strukContent');
        const now = new Date();
        const tanggal = now.toLocaleDateString('id-ID', { 
            day: '2-digit', 
            month: 'long', 
            year: 'numeric' 
        });
        const waktu = now.toLocaleTimeString('id-ID');

        let itemsHTML = '';
        const data = KasirState.transactionData;
        
        // Barang Stok
        data.barang.forEach(item => {
            const opt = Array.from(KasirState.elements.selectBarang.options).find(o => o.value == item.id_barang);
            const namaBarang = opt ? opt.dataset.nama : 'Unknown';
            itemsHTML += `
                <div class="flex justify-between text-xs mb-1">
                    <span>${namaBarang}</span>
                    <span>${Utils.formatRupiah(item.subtotal)}</span>
                </div>
                <div class="text-xs text-gray-500 mb-2 pl-2">
                    ${item.jumlah_kardus > 0 ? `${item.jumlah_kardus} Kardus × ${Utils.formatRupiah(item.harga_kardus)}` : ''}
                    ${item.jumlah_kardus > 0 && item.jumlah_ecer > 0 ? ' + ' : ''}
                    ${item.jumlah_ecer > 0 ? `${item.jumlah_ecer} Ecer × ${Utils.formatRupiah(item.harga_ecer)}` : ''}
                </div>
            `;
        });
        
        // Barang Penitipan
        data.penitipan.forEach(item => {
            const opt = Array.from(KasirState.elements.selectPenitipan.options).find(o => o.value == item.id_penitipan_detail);
            const namaBarang = opt ? opt.dataset.nama : 'Unknown';
            const penitip = opt ? opt.dataset.penitip : '';
            itemsHTML += `
                <div class="flex justify-between text-xs mb-1">
                    <span>${namaBarang} (Titipan)</span>
                    <span>${Utils.formatRupiah(item.subtotal)}</span>
                </div>
                <div class="text-xs text-gray-500 mb-2 pl-2">
                    ${item.jumlah} pcs × ${Utils.formatRupiah(item.harga_jual)} - Penitip: ${penitip}
                </div>
            `;
        });

        const totalBayar = parseInt(KasirState.elements.totalDisplay.innerText.replace(/\D/g, "")) || 0;
        const kembali = data.total_bayar - totalBayar;

        strukContent.innerHTML = `
            <div class="text-center mb-4">
                <h4 class="font-bold text-lg">${window.kasirConfig.appName}</h4>
                <p class="text-xs text-gray-600 dark:text-gray-400">Alamat Toko Anda</p>
                <p class="text-xs text-gray-600 dark:text-gray-400">Telp: 0812-xxxx-xxxx</p>
            </div>
            
            <div class="border-t border-b border-gray-300 dark:border-gray-600 py-2 mb-2 text-xs">
                <div class="flex justify-between">
                    <span>Tanggal:</span>
                    <span>${tanggal}</span>
                </div>
                <div class="flex justify-between">
                    <span>Waktu:</span>
                    <span>${waktu}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir:</span>
                    <span>${window.kasirConfig.userName}</span>
                </div>
                <div class="flex justify-between">
                    <span>No. Transaksi:</span>
                    <span>${transaksi.id_transaksi || 'N/A'}</span>
                </div>
            </div>

            <div class="mb-2">
                ${itemsHTML}
            </div>

            <div class="border-t border-gray-300 dark:border-gray-600 pt-2">
                <div class="flex justify-between text-sm mb-1">
                    <span>Subtotal:</span>
                    <span>${Utils.formatRupiah(data.total_harga)}</span>
                </div>
                ${KasirState.elements.metode.value === 'Qris' ? `
                <div class="flex justify-between text-xs mb-1">
                    <span>Biaya Admin:</span>
                    <span>${Utils.formatRupiah(1000)}</span>
                </div>
                ` : ''}
                <div class="flex justify-between font-bold text-lg mb-2">
                    <span>TOTAL:</span>
                    <span>${Utils.formatRupiah(totalBayar)}</span>
                </div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Bayar (${data.metode}):</span>
                    <span>${Utils.formatRupiah(data.total_bayar)}</span>
                </div>
                <div class="flex justify-between text-sm font-semibold">
                    <span>Kembali:</span>
                    <span>${Utils.formatRupiah(kembali)}</span>
                </div>
            </div>

            <div class="text-center mt-4 pt-2 border-t border-gray-300 dark:border-gray-600">
                <p class="text-xs text-gray-600 dark:text-gray-400">Terima kasih atas kunjungan Anda</p>
                <p class="text-xs text-gray-600 dark:text-gray-400">Barang yang sudah dibeli tidak dapat dikembalikan</p>
            </div>
        `;

        document.getElementById('printModal').classList.remove('hidden');
    },

    closePrintModal() {
        document.getElementById('printModal').classList.add('hidden');
        window.location.reload();
    }
};

// Global functions for onclick handlers
window.closeConfirmModal = () => TransactionManager.closeConfirmModal();
window.confirmTransaction = () => TransactionManager.confirmTransaction();
window.closePrintModal = () => TransactionManager.closePrintModal();
window.printStruk = () => {
    const strukContent = document.getElementById('strukContent').innerHTML;
    const printWindow = window.open('', '', 'width=300,height=600');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Struk Transaksi</title>
            <style>
                body { 
                    font-family: 'Courier New', monospace; 
                    font-size: 12px; 
                    padding: 10px;
                    max-width: 300px;
                    margin: 0 auto;
                }
                @media print {
                    body { 
                        margin: 0; 
                        padding: 5px;
                    }
                }
            </style>
        </head>
        <body>
            ${strukContent}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
};