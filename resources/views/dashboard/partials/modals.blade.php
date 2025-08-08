<!-- Modal Ganti Foto -->
<div x-show="showGantiFotoModal" style="display: none;" class="modal-backdrop">
    <div @click.away="showGantiFotoModal = false" class="modal-panel">
        <h3 class="modal-title">Ganti Foto Profil</h3>
        <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="mt-4">
            @csrf
            <input type="file" name="foto" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
            <div class="modal-footer">
                <button @click="showGantiFotoModal = false" type="button" class="btn btn-gray w-auto mt-0">Batal</button>
                <button type="submit" class="btn btn-blue w-auto mt-0 ml-2">Upload</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Reset Password -->
<div x-show="showResetPasswordModal" style="display: none;" class="modal-backdrop">
    <div @click.away="showResetPasswordModal = false" class="modal-panel">
        <h3 class="modal-title">Set Password Baru</h3>
        <form method="POST" action="{{ route('profile.password.set') }}" class="mt-4 space-y-4">
            @csrf
            <div>
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" id="password" required class="form-input">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>
            <div>
                <label for="password_confirmation" class="form-label">Ulangi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="form-input">
            </div>
            <div class="modal-footer">
                <button @click="showResetPasswordModal = false" type="button" class="btn btn-gray w-auto mt-0">Batal</button>
                <button type="submit" class="btn btn-blue w-auto mt-0 ml-2">Simpan Password</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Update PA -->
<div x-show="showUpdatePaModal" style="display: none;" class="modal-backdrop">
    <div @click.away="showUpdatePaModal = false" class="modal-panel">
        <h3 class="modal-title">Update Pembimbing Akademik</h3>
        <form method="POST" action="{{ route('profile.pa.update') }}" class="mt-4">
            @csrf @method('patch')
            <label for="pa_dosen" class="form-label">Pilih Dosen Pembimbing Akademik</label>
            <select name="PenasehatAkademik" id="pa_dosen" class="form-select">
                <option value="">-- Pilih Dosen --</option>
                @foreach ($dosens as $dosen)
                    <option value="{{ $dosen->Login }}" {{ $mahasiswa->PenasehatAkademik == $dosen->Login ? 'selected' : '' }}>
                        {{ $dosen->Nama }}, {{ $dosen->Gelar }}
                    </option>
                @endforeach
            </select>
            <div class="modal-footer">
                <button @click="showUpdatePaModal = false" type="button" class="btn btn-gray w-auto mt-0">Batal</button>
                <button type="submit" class="btn btn-blue w-auto mt-0 ml-2">Update</button>
            </div>
        </form>
    </div>
</div>
