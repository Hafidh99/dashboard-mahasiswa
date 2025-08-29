<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DosenLoginController extends Controller
{
    /**
     * Menampilkan halaman form login untuk dosen.
     */
    public function create(): View
    {
        return view('auth.login_dosen');
    }

    /**
     * Menangani permintaan login dari dosen.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'Login' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cari dosen berdasarkan 'Login' (username)
        $dosen = Dosen::find($request->Login);

        // 3. Buat hash dari password yang diinput
        $passwordInput = $request->password;
        $fullHash = '*' . strtoupper(sha1(sha1($passwordInput, true)));
        $hashedPassword = substr($fullHash, 0, 10);

        // 4. Cek apakah dosen ada dan password cocok
        if (! $dosen || $dosen->Password !== $hashedPassword) {
            // Jika gagal, kembalikan ke halaman login dengan pesan error
            throw ValidationException::withMessages([
                'Login' => __('auth.failed'),
            ]);
        }

        // 5. Jika berhasil, login menggunakan guard 'dosen'
        Auth::guard('dosen')->login($dosen, $request->boolean('remember'));

        // 6. Regenerate session
        $request->session()->regenerate();

        // 7. Redirect ke dashboard dosen (untuk sementara, kita buat route sederhana)
        return redirect()->intended(route('dosen.dashboard'));
    }

    /**
     * Menangani proses logout dosen.
     */
    public function destroy(Request $request)
    {
        Auth::guard('dosen')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
