<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => [
            'required',
            'min:3',
            'regex:/[A-Z]/' // harus ada huruf kapital
        ],
    ], [
        'password.min' => 'Password minimal 3 karakter.',
        'password.regex' => 'Password harus mengandung minimal 1 huruf kapital.',
    ]);

    $nim = "2455301030";
    $nimp = "2455301030A";

    // 🔹 Cek apakah username & password sesuai NIM
    if ($request->username === $nim && $request->password === $nimp) {
        return redirect()->route('login')->with('success', 'Login berhasil! Selamat datang.');
    } else {
        return redirect()->route('login')->with('error', 'Username atau Password salah!');
    }
}


    // Menampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Berhasil logout');
    }
}
