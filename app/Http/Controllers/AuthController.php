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

    public function process(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => [
                'required',
                'min:3',
            ],
        ], [
            'password.min' => 'Password minimal 3 karakter.',
        ]);

        $data = [
            ['username' => 'farhan', 'password' => '1234', 'role' => 'admin'],
            ['username' => 'hapis', 'password' => 'jawa', 'role' => 'karyawan'],
        ];

        foreach ($data as $no => $user) {
            if ($request->username === $user['username'] && $request->password === $user['password']) {
                if ($user['role'] === 'admin') {
                    return redirect()->route('admin.index')->with('success', 'Selamat Datang Admin!' . $user['username']);
                } else {
                    return redirect()->route('karyawan.index')->with('success', 'Selamat Datang Admin!' . $user['username']);
                }
            }
        }
        return redirect()->route('signin')->with('error', 'Username atau Password salah!');

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
