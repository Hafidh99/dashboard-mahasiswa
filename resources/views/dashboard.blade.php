<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status') === 'profile-updated')
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">Data profil berhasil diperbarui.</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div x-data="{ showGantiFotoModal: false, showResetPasswordModal: false, showUpdatePaModal: false }" class="md:col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        @if ($mahasiswa->Foto)
                            <img src="{{ asset('storage/' . $mahasiswa->Foto) }}" alt="Foto Profil" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                        @else
                            <img src="https://via.placeholder.com/150" alt="User profile picture" class="w-24 h-24 rounded-full mx-auto mb-4">
                        @endif
                        <h3 class="text-lg font-bold">{{ $mahasiswa->Nama }} ({{ $mahasiswa->MhswID }})</h3>
                        <p class="text-sm text-gray-600">{{ $mahasiswa->prodi ? $mahasiswa->prodi->Nama : 'Prodi tidak ditemukan' }}</p>
                        
                        <div class="grid grid-cols-3 gap-4 text-center mt-4 border-t pt-4">
                            <div>
                                <span class="font-bold text-blue-600 text-lg">{{ $semesterBerjalan ?? 'N/A' }}</span>
                                <span class="text-xs block">Semester</span>
                            </div>
                            <div>
                                <span class="font-bold text-blue-600 text-lg">{{ $mahasiswa->TahunID }}</span>
                                <span class="text-xs block">Tahun Masuk</span>
                            </div>
                            <div>
                                <span class="font-bold text-blue-600 text-lg">{{ $mahasiswa->BatasStudi }}</span>
                                <span class="text-xs block">Batas Studi</span>
                            </div>
                        </div>
                        <button @click="showGantiFotoModal = true" class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded-md text-sm">Ganti Foto</button>
                        <div x-show="showGantiFotoModal" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
                            <div @click.away="showGantiFotoModal = false" class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2">Ganti Foto Profil</h3>
                                <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="mt-4">
                                    @csrf
                                    <input type="file" name="foto" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    <div class="items-center px-4 py-3 mt-4 text-right">
                                        <button @click="showGantiFotoModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md mr-2">Batal</button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <button @click="showResetPasswordModal = true" class="w-full mt-2 px-4 py-2 bg-yellow-500 text-white rounded-md text-sm">Reset Password</button>
                        <div x-show="showResetPasswordModal" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
                            <div @click.away="showResetPasswordModal = false" class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2">Set Password Baru</h3>

                                @if ($errors->updatePassword->any())
                                    <div class="mt-4 text-sm text-red-600">
                                        Gagal memperbarui password. Pastikan kedua kolom password baru cocok.
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('profile.password.set') }}" class="mt-4 space-y-4">
                                    @csrf

                                    <div>
                                        <label for="password" class="text-sm font-medium">Password Baru</label>
                                        <input type="password" name="password" id="password" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="text-sm font-medium">Ulangi Password Baru</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    </div>

                                    <div class="items-center px-4 py-3 mt-4 text-right -mx-5 -mb-5 bg-gray-50 rounded-b-md">
                                        <button @click="showResetPasswordModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md mr-2">Batal</button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-bold border-b pb-2 mb-2">Pembimbing Akademik</h4>

                    @if ($mahasiswa->pembimbingAkademik)
                        <p class="text-gray-700 font-semibold">{{ $mahasiswa->pembimbingAkademik->Nama }}, {{ $mahasiswa->pembimbingAkademik->Gelar }}</p>
                    @else
                        <p class="text-gray-500 italic">Belum ditentukan</p>
                    @endif

                    <button @click="showUpdatePaModal = true" class="w-full mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                        Update PA
                    </button>
                </div>
                <div x-show="showUpdatePaModal" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
                    <div @click.away="showUpdatePaModal = false" class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2">Update Pembimbing Akademik</h3>
                        <form method="POST" action="{{ route('profile.pa.update') }}" class="mt-4">
                            @csrf
                            @method('patch')

                            <label for="pa_dosen" class="text-sm font-medium">Pilih Dosen Pembimbing Akademik</label>
                            <select name="PenasehatAkademik" id="pa_dosen" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->Login }}" {{ $mahasiswa->PenasehatAkademik == $dosen->Login ? 'selected' : '' }}>
                                        {{ $dosen->Nama }}, {{ $dosen->Gelar }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="items-center px-4 py-3 mt-4 text-right -mx-5 -mb-5 bg-gray-50 rounded-b-md">
                                <button @click="showUpdatePaModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md mr-2">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
                <div x-show="showGantiFotoModal" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
                    <div @click.away="showGantiFotoModal = false" class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2">Ganti Foto Profil</h3>
                        <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="mt-4">
                            @csrf
                            <input type="file" name="foto" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                            <div class="items-center px-4 py-3 mt-4 text-right">
                                <button @click="showGantiFotoModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md mr-2">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-6">
                
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div x-data="{ tab: 'pribadi' }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex" aria-label="Tabs">
                                    <button type="button" @click="tab = 'pribadi'" :class="{'border-blue-500 text-blue-600': tab === 'pribadi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'pribadi'}" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                                        Data Pribadi
                                    </button>
                                    <button type="button" @click="tab = 'orangtua'" :class="{'border-blue-500 text-blue-600': tab === 'orangtua', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'orangtua'}" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                                        Data Orang Tua
                                    </button>
                                </nav>
                            </div>

                            <div x-data="{ isEditing: false }" x-show="tab === 'pribadi'" class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-bold text-lg">Data Pribadi</h4>
                                    <button type="button" @click="isEditing = !isEditing" class="text-sm text-blue-600 hover:underline">
                                        <span x-show="!isEditing">Edit</span>
                                        <span x-show="isEditing" style="display: none;">Batal</span>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div><span class="font-semibold">Nama:</span> <input name="Nama" value="{{ old('Nama', $mahasiswa->Nama) }}" x-bind:readonly="!isEditing" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"><x-input-error :messages="$errors->get('Nama')" class="mt-2" /></div>
                                    <div><span class="font-semibold">Tempat Lahir:</span> <input name="TempatLahir" value="{{ old('TempatLahir', $mahasiswa->TempatLahir) }}" x-bind:readonly="!isEditing" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></div>
                                    <div><span class="font-semibold">Tanggal Lahir:</span> <input name="TanggalLahir" type="date" value="{{ old('TanggalLahir', $mahasiswa->TanggalLahir) }}" x-bind:readonly="!isEditing" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></div>
                                    <div><span class="font-semibold">Handphone:</span> <input name="Handphone" value="{{ old('Handphone', $mahasiswa->Handphone) }}" x-bind:readonly="!isEditing" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></div>
                                    
                                    <div><span class="font-semibold">Email:</span> <input name="Email" type="text" value="{{ old('Email', $mahasiswa->Email) }}" x-bind:readonly="!isEditing" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></div>
                                    
                                    <div class="md:col-span-2"><span class="font-semibold">Alamat:</span> <textarea name="Alamat" x-bind:readonly="!isEditing" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('Alamat', $mahasiswa->Alamat) }}</textarea></div>
                                </div>

                                <div x-show="isEditing" class="flex justify-end mt-6">
                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Simpan Perubahan</button>
                                </div>
                            </div>

                            <div x-data="{ isEditing: false }" x-show="tab === 'orangtua'" style="display: none;" class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-bold text-lg">Data Orang Tua</h4>
                                    <button type="button" @click="isEditing = !isEditing" class="text-sm text-blue-600 hover:underline">
                                        <span x-show="!isEditing">Edit</span>
                                        <span x-show="isEditing" style="display: none;">Batal</span>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                                    <div><span class="font-semibold block mb-1">Nama Ayah:</span> <input name="NamaAyah" value="{{ old('NamaAyah', $mahasiswa->NamaAyah) }}" x-bind:readonly="!isEditing" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></div>
                                    <div><span class="font-semibold block mb-1">Nama Ibu:</span> <input name="NamaIbu" value="{{ old('NamaIbu', $mahasiswa->NamaIbu) }}" x-bind:readonly="!isEditing" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></div>
                                    <div class="md:col-span-2"><span class="font-semibold block mb-1">Alamat Orang Tua:</span> <textarea name="AlamatOrtu" x-bind:readonly="!isEditing" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('AlamatOrtu', $mahasiswa->AlamatOrtu) }}</textarea></div>
                                </div>

                                <div x-show="isEditing" class="flex justify-end mt-6">
                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Simpan Perubahan</button>
                                </div>
                            </div>

                        </div>
                    </form>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h4 class="font-bold border-b pb-2 mb-4">Perkembangan Nilai Akademik</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1 text-center">
                                <span class="text-xs">Indeks Prestasi Kumulatif (IPK)</span>
                                <p class="text-5xl font-bold text-green-600">{{ $ipk }}</p>
                            </div>
                            <div class="md:col-span-2 h-64">
                                <canvas id="nilaiChart"
                                    data-labels='@json($labels)'
                                    data-ips='@json($dataIPS)'
                                    data-ipk='@json($dataIPK)'>
                                </canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="font-bold border-b pb-2 mb-4">Histori Keuangan Mahasiswa</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Akademik</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Tagihan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Potongan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terbayar</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Tagihan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                @forelse ($riwayatNilai as $khs)
                                    @php
                                        // Lakukan kalkulasi di sini
                                        $sisaTagihan = $khs->Biaya - $khs->Potongan - $khs->Bayar;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $khs->Sesi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $khs->TahunID }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($khs->Biaya, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($khs->Potongan, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($khs->Bayar, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($sisaTagihan, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($sisaTagihan <= 0)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Lunas
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Belum Lunas
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada histori keuangan yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>