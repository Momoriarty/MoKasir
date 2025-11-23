<x-app-layout>
    <div class="p-6 space-y-6 bg-gray-100 min-h-screen">

        {{-- HEADER --}}
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">📊 Dashboard Analytics</h1>
            <div class="flex gap-2">
                <button onclick="window.print()"
                    class="bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-700">
                    🖨️ Print
                </button>
                <button onclick="exportData()"
                    class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">
                    📥 Export Excel
                </button>
            </div>
        </div>

        {{-- FILTER PERIODE --}}
        <form id="filterForm" class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="font-semibold text-lg mb-4 text-gray-700">🔍 Filter Data</h3>

            {{-- Quick Filters --}}
            <div class="flex flex-wrap gap-2 mb-4">
                <button type="button" onclick="setQuickFilter('today')" class="quick-filter-btn">Hari Ini</button>
                <button type="button" onclick="setQuickFilter('week')" class="quick-filter-btn">Minggu Ini</button>
                <button type="button" onclick="setQuickFilter('month')" class="quick-filter-btn">Bulan Ini</button>
                <button type="button" onclick="setQuickFilter('year')" class="quick-filter-btn">Tahun Ini</button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-01') }}"
                        class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ date('Y-m-t') }}"
                        class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori"
                        class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $k)
                            <option value="{{ $k->kategori }}">{{ $k->kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1">Kasir</label>
                    <select name="user"
                        class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kasir</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1">Metode</label>
                    <select name="metode"
                        class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Metode</option>
                        <option value="Tunai">💵 Tunai</option>
                        <option value="Qris">📱 Qris</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1">Stok</label>
                    <select name="stok_status"
                        class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="semua">Semua Stok</option>
                        <option value="aman">✅ Aman</option>
                        <option value="kritis">⚠️ Kritis</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
                    🔄 Terapkan Filter
                </button>
                <button type="button" onclick="resetFilter()"
                    class="bg-gray-500 text-white px-6 py-2 rounded shadow hover:bg-gray-600 transition">
                    🗑️ Reset
                </button>
            </div>
        </form>

        {{-- LOADING OVERLAY --}}
        <div id="loadingOverlay"
            class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-xl">
                <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600 mx-auto"></div>
                <p class="mt-4 text-gray-700 font-semibold">Memuat data...</p>
            </div>
        </div>

        {{-- ALERTS --}}
        <div id="alertsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
            <div id="kritisAlert" class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow hidden">
                <h4 class="font-bold text-red-700 mb-2">⚠️ Stok Kritis</h4>
                <ul id="kritisList" class="text-sm text-red-600 list-disc list-inside"></ul>
            </div>
            <div id="habisAlert" class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded shadow hidden">
                <h4 class="font-bold text-orange-700 mb-2">🚨 Stok Habis</h4>
                <ul id="habisList" class="text-sm text-orange-600 list-disc list-inside"></ul>
            </div>
        </div>

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="kpi-card bg-gradient-to-br from-green-400 to-green-600">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="font-semibold text-white opacity-90">💰 Pendapatan</h4>
                        <p id="kpiPendapatan" class="text-2xl font-bold text-white">Rp 0</p>
                    </div>
                    <span id="growthPendapatan" class="growth-badge"></span>
                </div>
                <p class="text-sm text-white opacity-80" id="avgTransaksi">Rata-rata: Rp 0</p>
            </div>

            <div class="kpi-card bg-gradient-to-br from-red-400 to-red-600">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="font-semibold text-white opacity-90">💸 Pengeluaran</h4>
                        <p id="kpiPengeluaran" class="text-2xl font-bold text-white">Rp 0</p>
                    </div>
                    <span id="growthPengeluaran" class="growth-badge"></span>
                </div>
                <p class="text-sm text-white opacity-80">Modal operasional</p>
            </div>

            <div class="kpi-card bg-gradient-to-br from-blue-400 to-blue-600">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="font-semibold text-white opacity-90">📈 Margin / Laba</h4>
                        <p id="kpiMargin" class="text-2xl font-bold text-white">Rp 0</p>
                    </div>
                    <span id="growthMargin" class="growth-badge"></span>
                </div>
                <p class="text-sm text-white opacity-80" id="marginPercent">0% margin</p>
            </div>

            <div class="kpi-card bg-gradient-to-br from-yellow-400 to-yellow-600">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="font-semibold text-white opacity-90">📦 Barang Terjual</h4>
                        <p id="kpiTerjual" class="text-2xl font-bold text-white">0</p>
                    </div>
                    <span id="growthTerjual" class="growth-badge"></span>
                </div>
                <p class="text-sm text-white opacity-80" id="transaksiCount">0 transaksi</p>
            </div>
        </div>

        {{-- MAIN CHARTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="chart-container">
                <h4 class="chart-title">📊 Trend Pendapatan</h4>
                <canvas id="pendapatanChart"></canvas>
            </div>
            <div class="chart-container">
                <h4 class="chart-title">📈 Trend Margin</h4>
                <canvas id="marginChart"></canvas>
            </div>
            <div class="chart-container">
                <h4 class="chart-title">🏆 Top 5 Terlaris</h4>
                <canvas id="top5Chart"></canvas>
            </div>
        </div>

        {{-- ANALYTICS CHARTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="chart-container">
                <h4 class="chart-title">💳 Metode Pembayaran</h4>
                <canvas id="paymentChart"></canvas>
            </div>
            <div class="chart-container">
                <h4 class="chart-title">👥 Performa Kasir</h4>
                <canvas id="cashierChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="chart-container">
                <h4 class="chart-title">🏷️ Penjualan per Kategori</h4>
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="chart-container">
                <h4 class="chart-title">⏰ Jam Tersibuk</h4>
                <canvas id="peakHoursChart"></canvas>
            </div>
        </div>

        {{-- STOK CHART --}}
        <div class="chart-container" style="max-height: 500px;">
            <h4 class="chart-title">📦 Stok Barang</h4>
            <canvas id="stokChart" style="height: 400px;"></canvas>
        </div>

        {{-- DRILL-DOWN DETAIL --}}
        <div class="chart-container hidden" id="detailStokContainer">
            <div class="flex justify-between items-center mb-4">
                <h4 id="detailStokTitle" class="chart-title mb-0">Detail Barang</h4>
                <button onclick="closeDetail()" class="text-gray-500 hover:text-gray-700">✕ Tutup</button>
            </div>
            <canvas id="detailStokChart"></canvas>
        </div>
    </div>

    <style>
        .kpi-card {
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }

        .chart-container {
            background: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .chart-title {
            font-weight: 600;
            font-size: 1.125rem;
            margin-bottom: 1rem;
            color: #374151;
        }

        .quick-filter-btn {
            padding: 0.5rem 1rem;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quick-filter-btn:hover {
            background: #e5e7eb;
            border-color: #9ca3af;
        }

        .growth-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let charts = {};

        // Quick filter
        function setQuickFilter(period) {
            let start, end;
            const now = new Date(); // jangan ubah 'today' saat setDate, simpan aslinya

            switch (period) {
                case 'today':
                    start = end = now.toISOString().split('T')[0];
                    break;

                case 'week':
                    // Hari Minggu = 0, Senin = 1, dst.
                    const today = new Date();
                    let dayOfWeek = today.getDay(); // 0 = Sunday
                    dayOfWeek = dayOfWeek === 0 ? 7 : dayOfWeek; // ubah Sunday jadi 7
                    const firstDayOfWeek = new Date(today);
                    firstDayOfWeek.setDate(today.getDate() - dayOfWeek + 1); // Senin
                    const lastDayOfWeek = new Date(today);
                    lastDayOfWeek.setDate(firstDayOfWeek.getDate() + 6); // Minggu
                    start = firstDayOfWeek.toISOString().split('T')[0];
                    end = lastDayOfWeek.toISOString().split('T')[0];
                    

                case 'month':
                    const firstDayOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
                    const lastDayOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                    start = firstDayOfMonth.toISOString().split('T')[0];
                    end = lastDayOfMonth.toISOString().split('T')[0];
                    break;

                case 'year':
                    const firstDayOfYear = new Date(now.getFullYear(), 0, 1);
                    const lastDayOfYear = new Date(now.getFullYear(), 11, 31);
                    start = firstDayOfYear.toISOString().split('T')[0];
                    end = lastDayOfYear.toISOString().split('T')[0];
                    break;
            }


            document.getElementById('start_date').value = start;
            document.getElementById('end_date').value = end;
            loadDashboard();
        }

        function resetFilter() {
            document.getElementById('filterForm').reset();
            loadDashboard();
        }

        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        function destroyChart(name) {
            if (charts[name]) {
                charts[name].destroy();
                delete charts[name];
            }
        }

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function showGrowth(elementId, value) {
            const el = document.getElementById(elementId);
            if (!el) return;

            if (value > 0) {
                el.textContent = `↗ +${value}%`;
                el.style.background = 'rgba(34, 197, 94, 0.3)';
            } else if (value < 0) {
                el.textContent = `↘ ${value}%`;
                el.style.background = 'rgba(239, 68, 68, 0.3)';
            } else {
                el.textContent = '→ 0%';
                el.style.background = 'rgba(156, 163, 175, 0.3)';
            }
        }

        async function loadDashboard() {
            showLoading();
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const query = new URLSearchParams(formData).toString();

            try {
                // KPI
                const kpiRes = await fetch('/dashboard/kpi?' + query);
                const kpi = await kpiRes.json();

                document.getElementById('kpiPendapatan').innerText = formatRupiah(kpi.current.pendapatan);
                document.getElementById('kpiPengeluaran').innerText = formatRupiah(kpi.current.pengeluaran);
                document.getElementById('kpiMargin').innerText = formatRupiah(kpi.current.margin);
                document.getElementById('kpiTerjual').innerText = kpi.current.terjual.toLocaleString();
                document.getElementById('avgTransaksi').innerText = 'Rata-rata: ' + formatRupiah(kpi.current.avgTransaksi);
                document.getElementById('marginPercent').innerText = kpi.current.marginPercent.toFixed(1) + '% margin';
                document.getElementById('transaksiCount').innerText = kpi.current.transaksiCount + ' transaksi';

                // Growth badges
                showGrowth('growthPendapatan', kpi.growth.pendapatan);
                showGrowth('growthPengeluaran', kpi.growth.pengeluaran);
                showGrowth('growthMargin', kpi.growth.margin);
                showGrowth('growthTerjual', kpi.growth.terjual);

                // Main Charts
                const chartRes = await fetch('/dashboard/chart-data?' + query);
                const chartData = await chartRes.json();

                destroyChart('pendapatan');
                charts.pendapatan = new Chart(document.getElementById('pendapatanChart'), {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Pendapatan',
                            data: chartData.pendapatan,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { display: false } }
                    }
                });

                destroyChart('margin');
                charts.margin = new Chart(document.getElementById('marginChart'), {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Margin',
                            data: chartData.margin,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { display: false } }
                    }
                });

                destroyChart('top5');
                charts.top5 = new Chart(document.getElementById('top5Chart'), {
                    type: 'bar',
                    data: {
                        labels: chartData.top5Labels,
                        datasets: [{
                            label: 'Terjual',
                            data: chartData.top5Data,
                            backgroundColor: [
                                'rgba(251, 191, 36, 0.8)',
                                'rgba(251, 146, 60, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(168, 85, 247, 0.8)',
                                'rgba(99, 102, 241, 0.8)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { display: false } }
                    }
                });

                // Payment Method
                const paymentRes = await fetch('/dashboard/payment-method?' + query);
                const paymentData = await paymentRes.json();

                destroyChart('payment');
                charts.payment = new Chart(document.getElementById('paymentChart'), {
                    type: 'doughnut',
                    data: {
                        labels: paymentData.labels,
                        datasets: [{
                            data: paymentData.totals,
                            backgroundColor: ['rgba(34, 197, 94, 0.8)', 'rgba(59, 130, 246, 0.8)']
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: true }
                });

                // Cashier Performance
                const cashierRes = await fetch('/dashboard/cashier-performance?' + query);
                const cashierData = await cashierRes.json();

                destroyChart('cashier');
                charts.cashier = new Chart(document.getElementById('cashierChart'), {
                    type: 'bar',
                    data: {
                        labels: cashierData.labels,
                        datasets: [{
                            label: 'Total Penjualan',
                            data: cashierData.totalPenjualan,
                            backgroundColor: 'rgba(99, 102, 241, 0.8)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { display: false } }
                    }
                });

                // Category Analytics
                const categoryRes = await fetch('/dashboard/category-analytics?' + query);
                const categoryData = await categoryRes.json();

                destroyChart('category');
                charts.category = new Chart(document.getElementById('categoryChart'), {
                    type: 'pie',
                    data: {
                        labels: categoryData.labels,
                        datasets: [{
                            data: categoryData.pendapatan,
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(251, 191, 36, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(168, 85, 247, 0.8)',
                                'rgba(236, 72, 153, 0.8)'
                            ]
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: true }
                });

                // Peak Hours
                const peakRes = await fetch('/dashboard/peak-hours?' + query);
                const peakData = await peakRes.json();

                destroyChart('peakHours');
                charts.peakHours = new Chart(document.getElementById('peakHoursChart'), {
                    type: 'line',
                    data: {
                        labels: peakData.labels,
                        datasets: [{
                            label: 'Transaksi',
                            data: peakData.transaksiCount,
                            borderColor: 'rgb(168, 85, 247)',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { display: false } }
                    }
                });

                // Stok
                const stokRes = await fetch('/dashboard/stok-data?' + query);
                const stokData = await stokRes.json();

                destroyChart('stok');
                const ctx4 = document.getElementById('stokChart').getContext('2d');
                charts.stok = new Chart(ctx4, {
                    type: 'bar',
                    data: {
                        labels: stokData.labels,
                        datasets: [
                            { label: 'Kardus', data: stokData.stokKardus, backgroundColor: stokData.colorsKardus },
                            { label: 'Ecer', data: stokData.stokEcer, backgroundColor: stokData.colorsEcer }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } },
                        onClick: async (evt) => {
                            const points = charts.stok.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
                            if (points.length) {
                                const index = points[0].index;
                                const id_barang = stokData.ids[index];
                                await loadDetailStok(id_barang, query);
                            }
                        }
                    }
                });

                // Load alerts
                const alertsRes = await fetch('/dashboard/stok-alerts');
                const alertsData = await alertsRes.json();

                if (alertsData.kritis.length > 0 || alertsData.habis.length > 0) {
                    document.getElementById('alertsContainer').classList.remove('hidden');

                    if (alertsData.kritis.length > 0) {
                        document.getElementById('kritisAlert').classList.remove('hidden');
                        document.getElementById('kritisList').innerHTML = alertsData.kritis
                            .map(b => `<li>${b.nama} (Kardus: ${b.stok_kardus}, Ecer: ${b.stok_ecer})</li>`)
                            .join('');
                    }

                    if (alertsData.habis.length > 0) {
                        document.getElementById('habisAlert').classList.remove('hidden');
                        document.getElementById('habisList').innerHTML = alertsData.habis
                            .map(b => `<li>${b.nama} - ${b.kategori}</li>`)
                            .join('');
                    }
                }

            } catch (error) {
                console.error('Error loading dashboard:', error);
                console.error('Error details:', error.message);
                console.error('Error stack:', error.stack);
                alert('Terjadi kesalahan saat memuat dashboard:\n\n' + error.message + '\n\nCek Console (F12) untuk detail.');
            } finally {
                hideLoading();
            }
        }

        async function loadDetailStok(id_barang, query) {
            const detailRes = await fetch(`/dashboard/stok-detail/${id_barang}?` + query);
            const detail = await detailRes.json();

            document.getElementById('detailStokContainer').classList.remove('hidden');
            document.getElementById('detailStokTitle').innerText = '📦 Detail: ' + detail.nama_barang;

            destroyChart('detailStok');
            charts.detailStok = new Chart(document.getElementById('detailStokChart'), {
                type: 'line',
                data: {
                    labels: detail.labels,
                    datasets: [
                        { label: 'Masuk', data: detail.masuk, borderColor: 'rgb(34, 197, 94)', fill: false },
                        { label: 'Rusak', data: detail.rusak, borderColor: 'rgb(239, 68, 68)', fill: false },
                        { label: 'Terjual', data: detail.terjual, borderColor: 'rgb(59, 130, 246)', fill: false }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { tooltip: { mode: 'index', intersect: false } }
                }
            });

            document.getElementById('detailStokContainer').scrollIntoView({ behavior: 'smooth' });
        }

        function closeDetail() {
            document.getElementById('detailStokContainer').classList.add('hidden');
        }

        function exportData() {
            alert('Fitur export akan segera hadir!\n\nUntuk saat ini, Anda dapat menggunakan tombol Print untuk menyimpan sebagai PDF.');
        }

        document.getElementById('filterForm').addEventListener('submit', e => {
            e.preventDefault();
            loadDashboard();
        });

        // Initial load
        loadDashboard();

        // Auto refresh setiap 5 menit
        setInterval(() => {
            if (!document.hidden) {
                loadDashboard();
            }
        }, 300000);
    </script>
</x-app-layout>