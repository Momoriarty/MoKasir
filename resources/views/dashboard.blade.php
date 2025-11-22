<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-6">
            <x-kpi-card title="Jumlah Barang Terjual" :value="$jumlahBarangTerjual" />
            <x-kpi-card title="Jumlah Produk" :value="$jumlahProduk" />
            <x-kpi-card title="Barang Masuk Hari Ini" :value="$barangMasukHariIni" />
            <x-kpi-card title="Barang Rusak Hari Ini" :value="$barangRusakHariIni" />
            <x-kpi-card title="Penitipan Terjual Hari Ini" :value="$penitipanTerjualHariIni" />
             <x-kpi-card title="Pendapatan Hari Ini" :value="number_format($pendapatanHariIni, 0, ',', '.')"
                prefix="Rp" />
            <x-kpi-card title="Margin Hari Ini" :value="number_format($marginHariIni, 0, ',', '.')" prefix="Rp" />
        </div>

        {{-- Alert Stok Kritis --}}
        @if($stokKritis->count())
            <div class="mb-6 p-4 bg-red-100 text-red-800 rounded">
                <strong>Perhatian!</strong> Beberapa barang mendekati habis:
                <ul>
                    @foreach($stokKritis as $b)
                        <li>{{ $b->nama_barang }} — Stok: {{ $b->stok_kardus }} kardus + {{ $b->stok_ecer }} ecer</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tabel Barang Masuk & Rusak --}}
        <div class="mb-6 bg-white rounded shadow p-4">
            <h2 class="text-lg font-bold mb-2">Stok Barang Hari Ini</h2>
            <table class="table-auto w-full border">
                <thead>
                    <tr class="bg-gray-200">
                        <th>Nama Barang</th>
                        <th>Masuk Hari Ini</th>
                        <th>Rusak Hari Ini</th>
                        <th>Stok Kardus</th>
                        <th>Stok Ecer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangs as $b)
                        <tr>
                            <td>{{ $b->nama_barang }}</td>
                            <td>{{ $b->total_masuk_hari_ini }}</td>
                            <td>{{ $b->total_rusak_hari_ini }}</td>

                            <td>{{ $b->stok_kardus }}</td>
                            <td>{{ $b->stok_ecer }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tabel Transaksi Terbaru --}}
        <div class="mb-6 bg-white rounded shadow p-4">
            <h2 class="text-lg font-bold mb-2">Transaksi Terbaru</h2>
            <table class="table-auto w-full border">
                <thead>
                    <tr class="bg-gray-200">
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Total Bayar</th>
                        <th>Metode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksiTerbaru as $t)
                        <tr>
                            <td>{{ $t->id_transaksi }}</td>
                            <td>{{ $t->tanggal }}</td>
                            <td>{{ $t->user->name }}</td>
                            <td>Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</td>
                            <td>{{ $t->metode }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Chart --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded shadow p-4">
                <h2 class="text-lg font-bold mb-2">Pendapatan 7 Hari Terakhir</h2>
                <canvas id="pendapatanChart"></canvas>
            </div>
            <div class="bg-white rounded shadow p-4">
                <h2 class="text-lg font-bold mb-2">Barang Terlaris 7 Hari Terakhir</h2>
                <canvas id="barangChart"></canvas>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('pendapatanChart'), {
            type: 'line',
            data: {
                labels: @json($pendapatanLabels),
                datasets: [{ label: 'Pendapatan', data: @json($pendapatanValues), borderColor: 'rgb(34,197,94)', backgroundColor: 'rgba(34,197,94,0.2)', tension: 0.3 }]
            }
        });

        new Chart(document.getElementById('barangChart'), {
            type: 'bar',
            data: {
                labels: @json($barangLabels),
                datasets: [{ label: 'Jumlah Terjual', data: @json($barangValues), backgroundColor: 'rgba(59,130,246,0.7)', borderColor: 'rgb(59,130,246)', borderWidth: 1 }]
            },
            options: { indexAxis: 'y' }
        });
    </script>
</x-app-layout>