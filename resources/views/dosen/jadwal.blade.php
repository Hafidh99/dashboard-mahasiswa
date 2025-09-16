<x-dosen-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Jadwal Mengajar Dosen: {{ $namaDosen }}
</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                
                <!-- Form Filter Tahun -->
                <form method="GET" action="{{ route('dosen.jadwal.index') }}" class="mb-6">
                    <div class="flex items-end space-x-4">
                        <div class="flex-grow">
                            <label for="tahun_id" class="block text-sm font-medium text-gray-700">Pilih Tahun Akademik</label>
                            <select name="tahun_id" id="tahun_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @if($semuaTahun->isEmpty())
                                    <option disabled>Tidak ada data tahun akademik</option>
                                @else
                                    @foreach($semuaTahun as $tahun)
                                        <option value="{{ $tahun->TahunID }}" {{ optional($tahunTerpilih)->TahunID == $tahun->TahunID ? 'selected' : '' }}>
                                            {{ $tahun->TahunID }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Tampilkan
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tabel Jadwal Utama -->
                <h3 class="text-lg font-bold mb-4">
                    Mata Kuliah Dosen Utama Tahun Akademik: {{ optional($tahunTerpilih)->TahunID ?? 'Tidak ada' }}
                </h3>
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode MK</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Matakuliah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prodi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruang</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($jadwalMengajar as $jadwal)
                                <tr>
                                    <td class="px-4 py-4 text-center text-sm">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $jadwal->MKKode }}</td>
                                    <td class="px-4 py-4 text-sm font-medium">{{ $jadwal->Nama }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $jadwal->NamaDosen }}, <span class="text-red-600 font-semibold align-super text-xs">{{ $jadwal->Gelar }}</span></td>
                                    <td class="px-4 py-4 text-sm">{{ $jadwal->NamaProdi }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $jadwal->NamaKelas }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $hari[$jadwal->HariID] ?? 'N/A' }}</td>
                                    <td class="px-4 py-4 text-sm">{{ substr($jadwal->JamMulai, 0, 5) }} - {{ substr($jadwal->JamSelesai, 0, 5) }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $jadwal->RuangID }}</td>
                                    <td class="px-4 py-4 text-center text-sm space-y-1">
                                        <a href="{{ route('dosen.jadwal.presensi.index', $jadwal->JadwalID) }}" class="inline-block px-3 py-1 bg-indigo-700 text-white text-xs rounded hover:bg-indigo-800">Input Absen</a>
                                        <a href="{{ route('dosen.jadwal.rekap.absen', $jadwal->JadwalID) }}" target="_blank" class="inline-block px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">Rekap Absen</a>
                                        <a href="{{ route('dosen.jadwal.rekap.presensi.dosen', $jadwal->JadwalID) }}" target="_blank" class="inline-block px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">Batas Absen</a>
                                    </td>
                                    <td class="px-4 py-4 text-center text-sm space-y-1">
                                        <a href="{{ route('dosen.jadwal.nilai.edit', ['jadwal' => $jadwal->JadwalID]) }}" class="inline-block px-3 py-1 bg-slate-500 text-white text-xs rounded hover:bg-slate-600">Input Nilai</a>
                                        <a href="{{ route('dosen.jadwal.bobot.edit', $jadwal->JadwalID) }}" class="inline-block px-3 py-1 bg-teal-500 text-white text-xs rounded hover:bg-teal-600">Set Bobot Penilaian</a>
                                        <a href="{{ route('dosen.jadwal.cetak.nilai', $jadwal->JadwalID) }}" class="inline-block px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">Cetak Nilai</a>
                                        <a href="{{ route('dosen.jadwal.cetak.detail_nilai', $jadwal->JadwalID) }}" class="inline-block px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">Cetak Detail Nilai</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-8 text-gray-500">
                                        Tidak ada jadwal mengajar pada tahun ajaran ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Jadwal Tim Dosen -->
                <h3 class="text-lg font-bold mb-4 mt-10">
                    Mata Kuliah TIM Tahun Akademik: {{ optional($tahunTerpilih)->TahunID ?? 'Tidak ada' }}
                </h3>
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode MK</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Matakuliah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prodi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruang</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($jadwalTim as $jadwal)
                                <tr>
                                    <td class="px-4 py-4 text-center text-sm">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $jadwal->MKKode }}</td>
                                    <td class="px-4 py-4 text-sm font-medium">{{ $jadwal->Nama }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $jadwal->NamaDosen }}, <span class="text-red-600 font-semibold align-super text-xs">{{ $jadwal->Gelar }}</span></td>
                                    <td class="px-4 py-4 text-sm">{{ $jadwal->NamaProdi }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $jadwal->NamaKelas }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $hari[$jadwal->HariID] ?? 'N/A' }}</td>
                                    <td class="px-4 py-4 text-sm">{{ substr($jadwal->JamMulai, 0, 5) }} - {{ substr($jadwal->JamSelesai, 0, 5) }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $jadwal->RuangID }}</td>
                                    <td class="px-4 py-4 text-center text-sm space-y-1">
                                        <a href="{{ route('dosen.jadwal.presensi.index', $jadwal->JadwalID) }}" class="inline-block px-3 py-1 bg-indigo-700 text-white text-xs rounded hover:bg-indigo-800">Absen</a>
                                        <a href="{{ route('dosen.jadwal.rekap.absen', $jadwal->JadwalID) }}" target="_blank" class="inline-block px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">Rekap Absen</a>
                                        <a href="{{ route('dosen.jadwal.rekap.presensi.dosen', $jadwal->JadwalID) }}" target="_blank" class="inline-block px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">Batas Absen</a>
                                    </td>
                                    <td class="px-4 py-4 text-sm">????</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-8 text-gray-500">
                                        Tidak ada jadwal mengajar tim pada tahun ajaran ini.
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

</x-dosen-layout>