<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


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

        $data = [a
            ['username' => 'farhan', 'password' => '1234', 'role' => 'admin'],
<<<<<<< HEAD
            ['username' => 'arifin', 'password' => 'hama', 'role' => 'admin'],
=======
            ['username' => 'arifin', 'password' => 'anto', 'role' => 'admin'],
>>>>>>> b8c59d37780fac14c480552c767eca2a761afc9f
            ['username' => 'hapis', 'password' => 'jawa', 'role' => 'karyawan'],
        ];

        foreach ($data as $user) {
            if ($request->username === $user['username'] && $request->password === $user['password']) {
<<<<<<< HEAD
                // kirim data user ke halaman admin
                session(['user' => $user]);
                if ($user['role'] === 'admin') {
                    return view('admin.index', ['user' => $user]);
                } else {
                    return view('karyawan.index', ['user' => $user]);
=======

                Session::put('username', $user['username']);
                Session::put('role', $user['role']);

                if ($user['role'] === 'admin') {
                    return redirect()->route('admin.index');
                } else {
                    return redirect()->route('karyawan.index');
>>>>>>> b8c59d37780fac14c480552c767eca2a761afc9f
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
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
