<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cetak Kartu Rencana Studi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-content">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Semester</h3>
                    <form method="GET" action="{{ route('krs.cetak') }}" class="krs-selection-form">
                        <div>
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" id="nim" value="{{ $mahasiswa->MhswID }}" readonly class="form-input bg-gray-100">
                        </div>
                        <div>
                            <label for="semester" class="form-label">Semester</label>
                            <select id="semester" name="semester" class="form-select">
                                <option value="">Pilih Semester</option>
                                @foreach ($semesterList as $semester)
                                    <option value="{{ $semester }}" {{ $selectedSemester == $semester ? 'selected' : '' }}>
                                        {{ $semester }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pt-6">
                            <button type="submit" class="btn btn-blue w-auto mt-0">Pilih</button>
                        </div>
                    </form>
                </div>
            </div>

            @if ($krsDetail->isNotEmpty())
            <div class="mt-6 card">
                <div class="card-content">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Kartu Rencana Studi - Semester {{ $selectedSemester }}</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode MK</th>
                                    <th>Nama</th>
                                    <th>SKS</th>
                                    <th>Kelas</th>
                                    <th>Dosen Pengampu</th>
                                    <th>Ruang</th>
                                    <th>Hari</th>
                                    <th>Jam Kuliah</th>
                                    <th>Status KRS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $hari = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                @endphp
                                @foreach ($krsDetail as $index => $krs)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $krs->MKKode }}</td>
                                    <td>{{ $krs->Nama }}</td>
                                    <td>{{ $krs->SKS }}</td>
                                    <td>{{ $krs->Kelas }}</td>
                                    <td>{{ $krs->NamaDosen ? $krs->NamaDosen . ', ' . $krs->Gelar : '-' }}</td>
                                    <td>{{ $krs->RuangID }}</td>
                                    <td>{{ $hari[$krs->HariID] ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($krs->JamMulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($krs->JamSelesai)->format('H:i') }}</td>
                                    <td class="text-xs">
                                        @if($krs->aprv_pa == 'Y')
                                            <span class="font-semibold text-green-600">Y - Diterima</span>
                                            <span class="block text-gray-500">{{ \Carbon\Carbon::parse($krs->tgl_aprovePA)->format('d-m-Y H:i') }}</span>
                                        @elseif($krs->aprv_pa == 'N')
                                            <span class="font-semibold text-red-600">N - Ditolak</span>
                                        @else
                                            <span class="text-yellow-600">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 font-semibold">
                                <tr>
                                    <td colspan="3" class="text-right pr-6">Total SKS</td>
                                    <td>{{ $totalSks }}</td>
                                    <td colspan="6"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button class="btn btn-green w-auto mt-0">Cetak KRS</button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
