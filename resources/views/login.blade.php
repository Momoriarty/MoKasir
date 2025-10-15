<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login SJC</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-indigo-900 flex items-center justify-center">

  <div class="relative w-full max-w-md bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8 shadow-2xl">
    <!-- Glow effect -->
    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl blur opacity-30 animate-pulse"></div>

    <div class="relative z-10">
      <h2 class="text-3xl font-bold text-center text-white mb-6">Login ke <span class="text-pink-400">SJC</span></h2>

      @if (session('error'))
        <div class="bg-red-500/70 text-white text-center py-2 rounded-lg mb-4">
          {{ session('error') }}
        </div>
      @endif

      @if (session('success'))
        <div class="bg-green-500/70 text-white text-center py-2 rounded-lg mb-4">
          {{ session('success') }}
        </div>
      @endif

      <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
        @csrf
        <div>
          <label class="text-sm text-gray-200 font-semibold">Username</label>
          <input type="text" name="username" required
                 class="w-full mt-1 p-3 bg-white/10 border border-white/20 text-white rounded-lg placeholder-gray-400 focus:ring-2 focus:ring-pink-500 focus:outline-none"
                 placeholder="Masukkan username">
        </div>

        <div>
          <label class="text-sm text-gray-200 font-semibold">Password</label>
          <input type="password" name="password" required
                 class="w-full mt-1 p-3 bg-white/10 border border-white/20 text-white rounded-lg placeholder-gray-400 focus:ring-2 focus:ring-pink-500 focus:outline-none"
                 placeholder="Masukkan password">
        </div>

        <button type="submit"
                class="w-full py-3 bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold rounded-lg shadow-md hover:shadow-xl hover:scale-105 transition-all duration-200">
          Masuk Sekarang 🚀
        </button>
      </form>

      <p class="text-gray-400 text-center mt-6 text-sm">
        © 2025 <span class="text-pink-400 font-semibold">SJC Career Center</span>
      </p>
    </div>
  </div>

</body>
</html>
