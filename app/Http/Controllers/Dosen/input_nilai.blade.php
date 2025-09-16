<x-dosen-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Input Nilai Mahasiswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Detail Mata Kuliah -->
                    @if($detailJadwal)
                        <div class="mb-6 border-b pb-4 text-sm text-gray-600">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                <div><strong>Matakuliah:</strong></div>
                                <div>{{ $detailJadwal->NamaMataKuliah }}</div>
                                <div><strong>Program Studi:</strong></div>
                                <div>{{ $detailJadwal->NamaProdi }}</div>
                                <div><strong>Kelas/Tahun:</strong></div>
                                <div>{{ $detailJadwal->NamaKelas }} / {{ $detailJadwal->TahunID }}</div>
                                <div><strong>Hari/Jam:</strong></div>
                                <div>{{ $hari[$detailJadwal->HariID] ?? 'N/A' }} / {{ substr($detailJadwal->JamMulai, 0, 5) }} - {{ substr($detailJadwal->JamSelesai, 0, 5) }}</div>
                            </div>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif


                    <h3 class="text-lg font-bold mb-4 text-center uppercase tracking-wider">Daftar Nilai Mahasiswa</h3>

                    <form action="{{ route('dosen.nilai.update', $jadwal->JadwalID) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas 1</th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas 2</th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas 3</th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Presensi</th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UTS</th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UAS</th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($krs_mahasiswa as $krs)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-2 py-2 whitespace-nowrap text-sm">{{ $loop->iteration }}</td>
                                            <td class="px-2 py-2 whitespace-nowrap text-sm">{{ $krs->MhswID }}</td>
                                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $krs->NamaMahasiswa }}</td>
                                            
                                            {{-- Input Nilai --}}
                                            @php
                                                $nilaiFields = ['Tugas1', 'Tugas2', 'Tugas3', 'Presensi', 'UTS', 'UAS'];
                                            @endphp

                                            @foreach ($nilaiFields as $field)
                                            <td class="px-2 py-2 whitespace-nowrap">
                                                <input type="number" step="0.01" name="nilai[{{ $krs->KRSID }}][{{ $field }}]" 
                                                        value="{{ old('nilai.'.$krs->KRSID.'.'.$field, $krs->$field) }}"
                                                        class="w-20 text-center form-input rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </td>
                                            @endforeach

                                            {{-- Hasil Kalkulasi --}}
                                            <td class="px-2 py-2 whitespace-nowrap text-sm text-center font-bold">{{ number_format($krs->NilaiAkhir, 2) }}</td>
                                            <td class="px-2 py-2 whitespace-nowrap text-sm text-center font-bold">{{ $krs->GradeNilai }}</td>
                                            <td class="px-2 py-2 whitespace-nowrap text-sm text-center font-bold">{{ number_format($krs->BobotNilai, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">Belum ada mahasiswa yang mengambil mata kuliah ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8 flex justify-start space-x-4">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Simpan Nilai
                            </button>
                            <button type="button" class="inline-flex items-center px-6 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                Finalisasi Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dosen-layout>
