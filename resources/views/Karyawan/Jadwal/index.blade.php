@extends('layouts.karyawan-layout')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Jadwal Kuliah
    </h2>
@endsection

@section('content')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg">
            
            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Terjadi Kesalahan!</strong>
                    <ul class="list-disc ml-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Filter Form -->
            <form action="{{ route('karyawan.jadwal.index') }}" method="GET" class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4 border rounded-md bg-gray-50">
                    {{-- Baris 1 --}}
                    <div>
                        <label for="tahun_id" class="block text-sm font-medium text-gray-700">Tahun Akd:</label>
                        <select name="tahun_id" id="tahun_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach($semuaTahun as $tahun)
                                <option value="{{ $tahun->TahunID }}" {{ ($input['tahun_id'] ?? '') == $tahun->TahunID ? 'selected' : '' }}>
                                    {{ $tahun->TahunID }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="program_id" class="block text-sm font-medium text-gray-700">Prg. Pendidikan:</label>
                        <select name="program_id" id="program_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Semua Program --</option>
                            @foreach($semuaProgram as $program)
                                <option value="{{ $program->ProgramID }}" {{ ($input['program_id'] ?? '') == $program->ProgramID ? 'selected' : '' }}>
                                    {{ $program->Nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="prodi_id" class="block text-sm font-medium text-gray-700">Program Studi:</label>
                        <select name="prodi_id" id="prodi_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Semua Prodi --</option>
                            @foreach($semuaProdi as $prodi)
                                <option value="{{ $prodi->ProdiID }}" {{ ($input['prodi_id'] ?? '') == $prodi->ProdiID ? 'selected' : '' }}>
                                    {{ $prodi->Nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div></div>

                    {{-- Baris 2 --}}
                    <div>
                        <label for="hari_id" class="block text-sm font-medium text-gray-700">Hari:</label>
                        <select name="hari_id" id="hari_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Semua Hari --</option>
                            @foreach($hari as $id => $nama)
                                <option value="{{ $id }}" {{ ($input['hari_id'] ?? '') == $id ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas:</label>
                        <select name="kelas_id" id="kelas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($semuaKelas as $kelas)
                                <option value="{{ $kelas->KelasID }}" {{ ($input['kelas_id'] ?? '') == $kelas->KelasID ? 'selected' : '' }}>
                                    {{ $kelas->Nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filter_mk" class="block text-sm font-medium text-gray-700">Filter MK:</label>
                        <input type="text" name="filter_mk" id="filter_mk" value="{{ $input['filter_mk'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="Kode atau Nama MK">
                    </div>
                    <div>
                        <label for="semester_mk" class="block text-sm font-medium text-gray-700">Semester MK:</label>
                        <input type="text" name="semester_mk" id="semester_mk" value="{{ $input['semester_mk'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="Contoh: 1">
                    </div>

                    {{-- Baris 3 - Tombol --}}
                    <div class="col-span-full flex items-center space-x-2 pt-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Kirim Parameter
                        </button>
                        <a href="{{ route('karyawan.jadwal.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Reset Parameter
                        </a>
                        <span class="border-l border-gray-400 h-6"></span>
                        <button type="button" id="tambahJadwalBtn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Tambah Jadwal
                        </button>
                        <button type="button" id="tambahKelasBtn" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Tambah Kelas
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tabel Hasil Jadwal -->
            <div class="mt-6">
                @php $nomor = 1; @endphp
                @forelse($jadwalDikelompokkan as $hariId => $jadwalHarian)
                    <h3 class="text-lg font-bold mt-4 mb-2 bg-blue-600 text-white p-2 rounded-t-md">{{ $hari[$hariId] ?? 'Hari Tidak Dikenal' }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-2 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">#</th>
                                    <th class="px-2 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Ruang</th>
                                    <th class="px-2 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Jam</th>
                                    <th class="px-2 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Kode <sup class="text-red-500 font-semibold">Smt</sup></th>
                                    <th class="px-2 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Matakuliah</th>
                                    <th class="px-2 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Kelas</th>
                                    <th class="px-2 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">SKS</th>
                                    <th class="px-2 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Dosen</th>
                                    <th class="px-2 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Cetak</th>
                                    <th class="px-2 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Del</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($jadwalHarian as $jadwal)
                                    <tr>
                                        <td class="px-2 py-2 whitespace-nowrap align-top">
                                            <div class="flex items-center space-x-2">
                                                <span>{{ $nomor++ }}</span>
                                                <a href="#" class="text-blue-600 hover:text-blue-900 edit-jadwal-btn" 
                                                data-id="{{ $jadwal->JadwalID }}" 
                                                data-json-url="{{ route('karyawan.jadwal.json', $jadwal->JadwalID) }}"
                                                data-update-url="{{ route('karyawan.jadwal.update', $jadwal->JadwalID) }}"
                                                title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                                </a>
                                            </div>
                                            <span class="text-gray-500 text-xs block mt-1 font-bold">#{{ $jadwal->JadwalID }}</span>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap align-top">{{ $jadwal->RuangID }}<br><span class="text-red-600 text-xs font-semibold">{{ $jadwal->ProgramID }}</span></td>
                                        <td class="px-2 py-2 whitespace-nowrap align-top">{{ substr($jadwal->JamMulai, 0, 5) }} - {{ substr($jadwal->JamSelesai, 0, 5) }}</td>
                                        <td class="px-2 py-2 whitespace-nowrap align-top">{{ $jadwal->MKKode }} <sup class="text-red-600 font-bold">{{ $jadwal->Semester ?? '' }}</sup></td>
                                        <td class="px-2 py-2 whitespace-nowrap font-medium align-top">{{ $jadwal->Nama }}</td>
                                        <td class="px-2 py-2 whitespace-nowrap align-top">{{ $jadwal->NamaKelasDariRelasi }}<br><span class="text-red-500 font-semibold">{{ $jadwal->JumlahMhswKRS }}~{{ $jadwal->Kapasitas ?? 0 }}</span></td>
                                        <td class="px-2 py-2 whitespace-nowrap align-top">{{ $jadwal->SKS }}</td>
                                        <td class="px-2 py-2 whitespace-nowrap align-top">
                                            <div class="flex items-start space-x-2">
                                                <div>
                                                    @if(isset($teamDosen[$jadwal->JadwalID]) && $teamDosen[$jadwal->JadwalID]->isNotEmpty())
                                                        @foreach($teamDosen[$jadwal->JadwalID] as $dosen)
                                                            <div class="block {{ $dosen->JenisDosenID == 'DSN' ? 'font-bold' : '' }}">
                                                                {{ $dosen->Nama }}, <sup class="text-red-600 font-semibold">{{ $dosen->Gelar }}</sup>
                                                            </div>
                                                        @endforeach
                                                    @elseif($jadwal->DosenID && $jadwal->NamaDosen)
                                                        <div class="block font-bold">
                                                            {{ $jadwal->NamaDosen }}, <sup class="text-red-600 font-semibold">{{ $jadwal->Gelar }}</sup>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 italic">Dosen belum diatur.</span>
                                                    @endif
                                                </div>
                                                <a href="#" class="text-green-600 hover:text-green-800 edit-dosen-btn shrink-0" 
                                                    data-jadwal-id="{{ $jadwal->JadwalID }}" 
                                                    title="Edit Tim Dosen">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap align-top text-center">
                                            <a href="{{ route('karyawan.jadwal.cetak', $jadwal->JadwalID) }}" class="block text-blue-600 hover:text-blue-900 font-medium text-xs" title="Cetak Daftar Hadir">Daftar</a>
                                            <a href="#" class="block text-blue-600 hover:text-blue-900 font-medium text-xs" title="Cetak Kursi UAS">Kursi UAS</a>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap align-top text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <form action="{{ route('karyawan.jadwal.destroy', $jadwal->JadwalID) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @empty
                    @if(!empty($input['tahun_id']) || !empty($input['prodi_id']) || !empty($input['program_id']))
                        <p class="text-center text-gray-500 mt-8 py-4">Tidak ada jadwal yang ditemukan untuk parameter yang dipilih.</p>
                    @else
                        <p class="text-center text-gray-500 mt-8 py-4">Silakan pilih parameter di atas untuk menampilkan jadwal.</p>
                    @endif
                @endforelse
            </div>
        </div>
    </div>
</div>

@include('Karyawan.Jadwal.partial._modal_tambah_kelas')
@include('Karyawan.Jadwal.partial._modal_tambah_jadwal')
@include('Karyawan.Jadwal.partial._modal_edit_jadwal')
@include('Karyawan.Jadwal.partial._modal_cari_ruang')
@include('Karyawan.Jadwal.partial._modal_cari_matakuliah')
@include('Karyawan.Jadwal.partial._modal_cari_dosen')
@include('Karyawan.Jadwal.partial._modal_edit_dosen')

@endsection

@push('scripts')
<script>
    window.pageData = {
        routes: {
            searchRuang: '{{ route("karyawan.ruang.search") }}', 
            searchMk: '{{ route("karyawan.matakuliah.search") }}',
            searchDosen: '{{ route("karyawan.dosen.search") }}',
            getDosenTeam: '{{ url("karyawan/jadwal") }}/{jadwalId}/edit-dosen', 
            updateDosenTeam: '{{ url("karyawan/jadwal") }}/{jadwalId}/update-dosen'
        },
        currentProdiId: '{{ $input["prodi_id"] ?? "" }}'
    };
</script>
<script src="{{ asset('js/jadwal-search.js') }}"></script>
@endpush

