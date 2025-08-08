<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div x-data="{ tab: 'pribadi' }" class="card">
        
        <!-- Tombol Tab -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex" aria-label="Tabs">
                <button type="button" @click="tab = 'pribadi'" :class="{'border-blue-500 text-blue-600': tab === 'pribadi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'pribadi'}" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">Data Pribadi</button>
                <button type="button" @click="tab = 'orangtua'" :class="{'border-blue-500 text-blue-600': tab === 'orangtua', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'orangtua'}" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">Data Orang Tua</button>
            </nav>
        </div>

        <!-- KONTEN TAB DATA PRIBADI -->
        <div x-data="{ isEditing: false }" x-show="tab === 'pribadi'" class="card-content">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-bold text-lg">Data Pribadi</h4>
                <button type="button" @click="isEditing = !isEditing" class="text-sm text-blue-600 hover:underline" x-text="isEditing ? 'Batal' : 'Edit'"></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><label class="form-label">Nama:</label> <input name="Nama" value="{{ old('Nama', $mahasiswa->Nama) }}" x-bind:readonly="!isEditing" class="form-input"><x-input-error :messages="$errors->get('Nama')" class="mt-2" /></div>
                <div><label class="form-label">Tempat Lahir:</label> <input name="TempatLahir" value="{{ old('TempatLahir', $mahasiswa->TempatLahir) }}" x-bind:readonly="!isEditing" class="form-input"><x-input-error :messages="$errors->get('TempatLahir')" class="mt-2" /></div>
                <div><label class="form-label">Tanggal Lahir:</label> <input name="TanggalLahir" type="date" value="{{ old('TanggalLahir', $mahasiswa->TanggalLahir) }}" x-bind:readonly="!isEditing" class="form-input"><x-input-error :messages="$errors->get('TanggalLahir')" class="mt-2" /></div>
                <div><label class="form-label">Handphone:</label> <input name="Handphone" value="{{ old('Handphone', $mahasiswa->Handphone) }}" x-bind:readonly="!isEditing" class="form-input"><x-input-error :messages="$errors->get('Handphone')" class="mt-2" /></div>
                <div><label class="form-label">Email:</label> <input name="Email" type="text" value="{{ old('Email', $mahasiswa->Email) }}" x-bind:readonly="!isEditing" class="form-input"><x-input-error :messages="$errors->get('Email')" class="mt-2" /></div>
                <div class="md:col-span-2"><label class="form-label">Alamat:</label> <textarea name="Alamat" x-bind:readonly="!isEditing" class="form-textarea">{{ old('Alamat', $mahasiswa->Alamat) }}</textarea><x-input-error :messages="$errors->get('Alamat')" class="mt-2" /></div>
            </div>
            <div class="flex justify-end mt-6" x-show="isEditing">
                <button type="submit" class="btn btn-blue w-auto mt-0">Simpan Perubahan</button>
            </div>
        </div>

        <!-- KONTEN TAB DATA ORANG TUA -->
        <div x-data="{ isEditing: false }" x-show="tab === 'orangtua'" class="card-content" style="display: none;">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-bold text-lg">Data Orang Tua</h4>
                <button type="button" @click="isEditing = !isEditing" class="text-sm text-blue-600 hover:underline" x-text="isEditing ? 'Batal' : 'Edit'"></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                <div><label class="form-label">Nama Ayah:</label> <input name="NamaAyah" value="{{ old('NamaAyah', $mahasiswa->NamaAyah) }}" x-bind:readonly="!isEditing" class="form-input"><x-input-error :messages="$errors->get('NamaAyah')" class="mt-2" /></div>
                <div><label class="form-label">Nama Ibu:</label> <input name="NamaIbu" value="{{ old('NamaIbu', $mahasiswa->NamaIbu) }}" x-bind:readonly="!isEditing" class="form-input"><x-input-error :messages="$errors->get('NamaIbu')" class="mt-2" /></div>
                <div class="md:col-span-2"><label class="form-label">Alamat Orang Tua:</label> <textarea name="AlamatOrtu" x-bind:readonly="!isEditing" class="form-textarea">{{ old('AlamatOrtu', $mahasiswa->AlamatOrtu) }}</textarea><x-input-error :messages="$errors->get('AlamatOrtu')" class="mt-2" /></div>
            </div>
            <div class="flex justify-end mt-6" x-show="isEditing">
                <button type="submit" class="btn btn-blue w-auto mt-0">Simpan Perubahan</button>
            </div>
        </div>

    </div>
</form>
