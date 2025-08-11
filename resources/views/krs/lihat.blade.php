<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kartu Rencana Studi (KRS) Aktif') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-content">
                    @if ($tahunAktif && $krsAktif->isNotEmpty())
                        <div class="krs-info-header">
                            <div><span class="label">Nama Mahasiswa</span>: {{ $mahasiswa->Nama }}</div>
                            <div><span class="label">Program Studi</span>: {{ $mahasiswa->prodi->Nama ?? '-' }}</div>
                            <div><span class="label">NIM</span>: {{ $mahasiswa->MhswID }}</div>
                            <div><span class="label">Tahun Akademik</span>: {{ $tahunAktif->Nama ?? '-' }}</div>
                            <div>
                                <span class="label">Penasehat Akademik</span>: 
                                @if ($mahasiswa->pembimbingAkdemik)
                                    {{ $mahasiswa->pembimbingAkademik->Nama }}, {{ $mahasiswa->pembimbingAkademik->Gelar }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode MK</th>
                                        <th>Matakuliah</th>
                                        <th>SKS</th>
                                        <th>Kelas</th>
                                        <th>Dosen Pengampu</th>
                                        <th>Ruang</th>
                                        <th>Hari</th>
                                        <th>Jam Kuliah</th>
                                        <th>Status KRS</th>
                                        @if(!$krsDisetujui)
                                            <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $hari = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                    @endphp
                                    @foreach ($krsAktif as $index => $krs)
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
                                                    <span class="krs-status-approved">Y - Diterima</span>
                                                    <span class="krs-status-date">{{ \Carbon\Carbon::parse($krs->tgl_aprovePA)->format('d-m-Y H:i') }}</span>
                                                @elseif($krs->aprv_pa == 'N')
                                                    <span class="krs-status-rejected">N - Ditolak</span>
                                                @else
                                                    <span class="krs-status-pending">Menunggu</span>
                                                @endif
                                            </td>
                                            @if(!$krsDisetujui)
                                                <td>
                                                    <form method="POST" action="{{ route('krs.hapus', $krs->KRSID) }}" onsubmit="return confirm('Anda yakin ingin menghapus mata kuliah ini?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-red" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Hapus</button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">Total SKS</td>
                                        <td>{{ $totalSks }}</td>
                                        <td colspan="{{ $krsDisetujui ? 6 : 7 }}"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <div class="action-buttons">
                            @if(!$krsDisetujui)
                                <a href="{{ route('krs.ambil') }}" class="btn btn-blue">Tambah MK</a>
                            @endif
                            <button class="btn btn-green">Cetak KRS</button>
                        </div>
                    @else
                        <div class="empty-state">
                            <p class="empty-state-text">Anda belum melakukan pengisian KRS untuk semester ini.</p>
                            <a href="{{ route('krs.isi') }}" class="btn btn-blue">
                                Isi KRS Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
    