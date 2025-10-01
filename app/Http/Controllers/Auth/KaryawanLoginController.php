<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class KaryawanLoginController extends Controller
{
    /**
     * Menampilkan halaman form login untuk karyawan.
     */
    public function create(): View
    {
        return view('auth.login_karyawan');
    }

    /**
     * Menangani permintaan login dari karyawan.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'Login' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cari karyawan berdasarkan 'Login' (username)
        $karyawan = Karyawan::find($request->Login);

        // 3. Hashing password kustom sesuai yang Anda berikan
        $passwordInput = $request->password;
        $fullHash = '*' . strtoupper(sha1(sha1($passwordInput, true)));
        $hashedPassword = substr($fullHash, 0, 10);

        // 4. Cek apakah karyawan ada dan password cocok
        if (! $karyawan || $karyawan->Password !== $hashedPassword) {
            // Jika gagal, kembalikan ke halaman login dengan pesan error
            throw ValidationException::withMessages([
                'Login' => __('auth.failed'),
            ]);
        }

        // 5. Jika berhasil, login menggunakan guard 'karyawan'
        Auth::guard('karyawan')->login($karyawan, $request->boolean('remember'));

        // 6. Regenerate session
        $request->session()->regenerate();

        // 7. Redirect ke dashboard karyawan
        return redirect()->intended(route('karyawan.dashboard'));
    }

    /**
     * Menangani proses logout karyawan.
     */
    public function destroy(Request $request)
    {
        Auth::guard('karyawan')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
