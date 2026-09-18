<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role?->slug;

            return match ($role) {
                'ketua_pokja' => redirect()->intended(route('ketua.dashboard')),
                'admin_pokja' => redirect()->intended('/dashboard-pokja'),
                'admin_tu' => redirect()->intended(route('admin-tu.dashboard')),
                'superadmin' => redirect()->intended(route('superadmin.dashboard')),
                default => abort(403),
            };
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        // 1. Logout user dari guard web
        Auth::logout();

        // 2. Invalidate session agar data session lama terhapus total
        $request->session()->invalidate();

        // 3. Regenerasi CSRF token untuk mencegah serangan CSRF
        $request->session()->regenerateToken();

        // 4. Arahkan kembali ke halaman login
        return redirect()->route('login');
    }
}