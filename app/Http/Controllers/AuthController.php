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
    public function showRegister()
    {
        return view('register');
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
            ['username' => 'arifin', 'password' => 'hama', 'role' => 'admin'],
            ['username' => 'hapis', 'password' => 'jawa', 'role' => 'karyawan'],
        ];

        foreach ($data as $user) {
            if ($request->username === $user['username'] && $request->password === $user['password']) {
                // kirim data user ke halaman admin
                session(['user' => $user]);
                if ($user['role'] === 'admin') {
                    return view('admin.index', ['user' => $user]);
                } else {
                    return view('karyawan.index', ['user' => $user]);
                }
            }
        }

        return redirect()->route('signin')->with('error', 'Username atau Password salah!');

    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        return redirect()->route('signin')->with('success', 'Registrasi berhasil, silakan login.');
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
