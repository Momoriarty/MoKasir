<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SJC</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500">

    <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl shadow-2xl w-full max-w-sm border border-white/20">
        <h2 class="text-3xl font-bold text-white text-center mb-6">✨ Login SJC ✨</h2>

        @if (session('error'))
            <div class="bg-red-500/80 text-white text-center py-2 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-500/80 text-white text-center py-2 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="text-white text-sm font-semibold">Username</label>
                <input type="text" name="username" required
                    class="w-full mt-1 p-3 rounded-lg bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-pink-400"
                    placeholder="Masukkan username...">
            </div>

            <div>
                <label class="text-white text-sm font-semibold">Password</label>
                <input type="password" name="password" required
                    class="w-full mt-1 p-3 rounded-lg bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-pink-400"
                    placeholder="Masukkan password...">
            </div>

            <button type="submit"
                class="w-full py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold rounded-lg shadow-lg hover:scale-105 transition-transform duration-200">
                Login Sekarang 🚀
            </button>
        </form>
    </div>
</body>

</html>
