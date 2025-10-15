<!DOCTYPE html>
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