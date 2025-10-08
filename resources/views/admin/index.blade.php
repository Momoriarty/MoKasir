<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin UMKM Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-700 text-white flex flex-col">
            <div class="p-6 border-b border-blue-500">
                <h1 class="text-2xl font-bold">UMKM Admin</h1>
                <p class="text-sm text-blue-200">Sistem Manajemen Usaha</p>
            </div>
            <nav class="flex-1 p-4 space-y-3">
                <a href="#" class="block px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 transition">🏠
                    Dashboard</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-500 transition">🧾 Data Produk</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-500 transition">👥 Data Pelanggan</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-500 transition">💰 Transaksi</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-500 transition">📊 Laporan</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-500 transition">⚙️ Pengaturan</a>
            </nav>
            <form action="{{ route('logout') }}" method="POST" class="p-4 border-t border-blue-500">
                @csrf
                <button class="w-full py-2 bg-red-500 hover:bg-red-600 rounded-lg font-semibold">
                    Keluar
                </button>
            </form>
        </aside>

        <!-- Konten utama -->
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-700">Dashboard Admin</h2>
                    <h2 class="text-2xl font-bold text-gray-700">
                        Selamat datang, {{ $user['username'] }} 👋
                    </h2>
                    <p class="text-gray-600 mt-1">Role kamu: {{ $user['role'] }}</p>

                </div>
                <div>
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistik singkat -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h3 class="text-sm text-gray-500">Total Produk</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">120</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h3 class="text-sm text-gray-500">Total Pelanggan</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">85</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h3 class="text-sm text-gray-500">Transaksi Bulan Ini</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">54</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h3 class="text-sm text-gray-500">Pendapatan</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">Rp 12.450.000</p>
                </div>
            </div>

            <!-- Tabel produk -->
            <div class="mt-10 bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">Data Produk UMKM</h3>
                <table class="w-full border border-gray-200 text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="py-2 px-3 border-b">No</th>
                            <th class="py-2 px-3 border-b">Nama Produk</th>
                            <th class="py-2 px-3 border-b">Kategori</th>
                            <th class="py-2 px-3 border-b">Harga</th>
                            <th class="py-2 px-3 border-b">Stok</th>
                            <th class="py-2 px-3 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-3 border-b">1</td>
                            <td class="py-2 px-3 border-b">Keripik Pisang</td>
                            <td class="py-2 px-3 border-b">Makanan Ringan</td>
                            <td class="py-2 px-3 border-b">Rp 10.000</td>
                            <td class="py-2 px-3 border-b">30</td>
                            <td class="py-2 px-3 border-b">
                                <button
                                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</button>
                                <button
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-3 border-b">2</td>
                            <td class="py-2 px-3 border-b">Sambal Botol</td>
                            <td class="py-2 px-3 border-b">Bumbu Dapur</td>
                            <td class="py-2 px-3 border-b">Rp 15.000</td>
                            <td class="py-2 px-3 border-b">50</td>
                            <td class="py-2 px-3 border-b">
                                <button
                                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</button>
                                <button
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>
