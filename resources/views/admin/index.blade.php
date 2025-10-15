<!DOCTYPE html>
<<<<<<< HEAD
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
=======
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: "Inter", system-ui, sans-serif;
        }

        .sidebar {
            width: 240px;
            min-height: 100vh;
            border-right: 1px solid var(--bs-border-color);
            background: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        .nav-link {
            color: var(--bs-body-color) !important;
            border-radius: 10px;
        }

        .nav-link.active,
        .nav-link:hover {
            background-color: var(--bs-secondary-bg);
        }

        .profile-box {
            background: var(--bs-secondary-bg);
            border-radius: 10px;
            padding: 12px;
        }

        .card {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            border: 1px solid var(--bs-border-color);
        }

        table {
            color: var(--bs-body-color);
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h5 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</h5>

            <div class="profile-box mb-4 text-center">
                <img src="https://i.pravatar.cc/60" class="rounded-circle mb-2" alt="Avatar">
                <h6 id="username">Welcome, {{ ucfirst(session('username')) }}</h6>
                <small id="role" class="text-muted">Role: {{ ucfirst(session('role')) }}</small>
            </div>

            <nav class="nav flex-column">
                <a class="nav-link active" href="#"><i class="bi bi-house-door me-2"></i>Dashboard</a>
                <a class="nav-link" href="#"><i class="bi bi-people me-2"></i>Users</a>
                <a class="nav-link" href="#"><i class="bi bi-bar-chart me-2"></i>Reports</a>
                <a class="nav-link" href="#"><i class="bi bi-wallet2 me-2"></i>Payments</a>
                <a class="nav-link" href="#"><i class="bi bi-gear me-2"></i>Settings</a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-grow-1">
            <nav class="navbar bg-body-tertiary px-4 d-flex justify-content-between">
                <span class="fw-semibold fs-5">Dashboard Overview</span>
                <div class="d-flex align-items-center gap-3">
                    <button id="themeToggle" class="btn btn-outline-secondary rounded-circle">
                        <i class="bi bi-moon"></i>
                    </button>
                    <button class="btn btn-outline-danger"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <!-- Cards -->
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h6>Total Users</h6>
                            <h3>8,412</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h6>Active Sessions</h6>
                            <h3>1,203</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h6>New Signups</h6>
                            <h3>322</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h6>Revenue</h6>
                            <h3>$12.4k</h3>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="card p-3">
                            <h6>Weekly Activity</h6><canvas id="lineChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3">
                            <h6>User Plans</h6><canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card mt-4 p-3">
                    <h6>Recent Activity</h6>
                    <table class="table table-borderless align-middle mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Siti A.</td>
                                <td>Created Account</td>
                                <td>2025-10-01</td>
                            </tr>
                            <tr>
                                <td>Budi R.</td>
                                <td>Paid Invoice #982</td>
                                <td>2025-10-03</td>
                            </tr>
                            <tr>
                                <td>Andi P.</td>
                                <td>Reported Issue #128</td>
                                <td>2025-10-05</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const getThemeColors = () => {
            const style = getComputedStyle(document.body);
            return {
                text: style.getPropertyValue("--bs-body-color").trim(),
                grid: style.getPropertyValue("--bs-border-color").trim(),
            };
        };

        let lineChart, pieChart;
        const drawCharts = () => {
            const { text, grid } = getThemeColors();
            if (lineChart) lineChart.destroy();
            if (pieChart) pieChart.destroy();

            lineChart = new Chart(document.getElementById("lineChart"), {
                type: "line",
                data: {
                    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                    datasets: [{
                        label: "Active Users",
                        data: [400, 300, 500, 450, 600, 700, 650],
                        borderColor: "#0d6efd",
                        backgroundColor: "rgba(13,110,253,0.25)",
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    plugins: { legend: { labels: { color: text } } },
                    scales: {
                        x: { ticks: { color: text }, grid: { color: grid } },
                        y: { ticks: { color: text }, grid: { color: grid } }
                    }
                }
            });

            pieChart = new Chart(document.getElementById("pieChart"), {
                type: "doughnut",
                data: {
                    labels: ["Free", "Basic", "Pro", "Enterprise"],
                    datasets: [{
                        data: [400, 300, 300, 200],
                        backgroundColor: ["#0d6efd", "#198754", "#ffc107", "#dc3545"]
                    }]
                },
                options: { plugins: { legend: { labels: { color: text } } } }
            });
        };
        drawCharts();

        // Toggle Tema
        document.getElementById("themeToggle").addEventListener("click", () => {
            const html = document.documentElement;
            const current = html.getAttribute("data-bs-theme");
            const next = current === "dark" ? "light" : "dark";
            html.setAttribute("data-bs-theme", next);
            document.getElementById("themeToggle").innerHTML =
                next === "dark" ? '<i class="bi bi-moon"></i>' : '<i class="bi bi-sun"></i>';
            drawCharts(); // refresh chart warna
        });
    </script>
</body>

</html>
>>>>>>> b8c59d37780fac14c480552c767eca2a761afc9f
