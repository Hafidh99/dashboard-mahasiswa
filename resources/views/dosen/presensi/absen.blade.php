<x-dosen-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Absensi Mahasiswa - Pertemuan Ke-{{ $presensi->Pertemuan }}
</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-4">
                    <p><strong>Presensi ID:</strong> {{ $presensi->PresensiID }}</p>
                    <p><strong>Jadwal ID:</strong> {{ $presensi->JadwalID }}</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-300 rounded-md p-3">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('dosen.presensi.absen.update', $presensi->PresensiID) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mahasiswa</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($daftarAbsensi as $absen)
                                    <tr>
                                        <td class="px-4 py-4 text-center text-sm">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4 text-sm">{{ $absen->MhswID }}</td>
                                        <td class="px-4 py-4 text-sm font-medium">{{ $absen->Nama }}</td>
                                        <td class="px-4 py-4 text-sm">
                                            <select name="kehadiran[{{ $absen->MhswID }}]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                @foreach ($jenisPresensi as $jenis)
                                                    <option value="{{ $jenis->JenisPresensiID }}" {{ $absen->JenisPresensiID == $jenis->JenisPresensiID ? 'selected' : '' }}>
                                                        {{ $jenis->Nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-8 text-gray-500">
                                            Tidak ada mahasiswa yang terdaftar di kelas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <a href="{{ route('dosen.jadwal.presensi.index', $presensi->JadwalID) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300">
                            Kembali
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Simpan Absensi
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

</x-dosen-layout>