<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        $request->user()->fill($request->validated());


        if ($request->user()->isDirty('Email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('dashboard')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'foto' => ['required', 'image', 'max:1024'], 
        ]);

        $user = $request->user();

        if ($user->Foto) {
            Storage::disk('public')->delete($user->Foto);
        }


        $path = $request->file('foto')->store('foto-profil', 'public');

        $user->Foto = $path;


        try {

            $user->save();

        } catch (\Illuminate\Database\QueryException $e) {

            dd($e->getMessage());
        }

        return Redirect::route('dashboard')->with('status', 'photo-updated');
    }

    public function setPassword(Request $request): RedirectResponse
    {

        $request->validateWithBag('updatePassword', [
            'password' => ['required', 'confirmed'],
        ]);

        $user = $request->user();
        $newPassword = $request->password;

        DB::update('UPDATE mhsw SET Password = LEFT(PASSWORD(?), 10) WHERE MhswID = ?', [
            $newPassword,
            $user->MhswID
        ]);

        return Redirect::route('dashboard')->with('status', 'password-updated');
    }
    public function updatePa(Request $request): RedirectResponse
    {
        // Validasi: Pastikan ID Dosen yang dikirim ada di tabel dosen
        $request->validate([
            'PenasehatAkademik' => ['required', 'string', 'exists:dosen,Login'],
        ]);

        $user = $request->user();
        
        // Update kolom PenasehatAkademik
        $user->PenasehatAkademik = $request->PenasehatAkademik;
        $user->save();

        return Redirect::route('dashboard')->with('status', 'pa-updated');
    }
}
