<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 info-box info-box-green" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- ========== KOLOM KIRI ========== -->
                <div x-data="{ showGantiFotoModal: false, showResetPasswordModal: false, showUpdatePaModal: false }" class="md:col-span-1 space-y-6">
                    <!-- Box Info Mahasiswa -->
                    <div class="card profile-card card-content">
                        @if ($mahasiswa->Foto)
                            <img src="{{ asset('storage/' . $mahasiswa->Foto) }}" alt="Foto Profil" class="profile-photo">
                        @else
                            <img src="https://via.placeholder.com/150" alt="User profile picture" class="profile-photo">
                        @endif
                        <h3 class="profile-name">{{ $mahasiswa->Nama }} ({{ $mahasiswa->MhswID }})</h3>
                        <p class="profile-prodi">{{ $mahasiswa->prodi ? $mahasiswa->prodi->Nama : 'Prodi tidak ditemukan' }}</p>
                        
                        <div class="profile-stats">
                            <div>
                                <span class="profile-stats-value">{{ $semesterBerjalan ?? 'N/A' }}</span>
                                <span class="profile-stats-label">Semester</span>
                            </div>
                            <div>
                                <span class="profile-stats-value">{{ $mahasiswa->TahunID }}</span>
                                <span class="profile-stats-label">Tahun Masuk</span>
                            </div>
                            <div>
                                <span class="profile-stats-value">{{ $mahasiswa->BatasStudi }}</span>
                                <span class="profile-stats-label">Batas Studi</span>
                            </div>
                        </div>
                        <button @click="showGantiFotoModal = true" class="btn btn-blue">Ganti Foto</button>
                        <button @click="showResetPasswordModal = true" class="btn btn-yellow">Reset Password</button>
                    </div>

                    <!-- Box Pembimbing Akademik -->
                    <div class="card card-content">
                        <h4 class="card-header">Pembimbing Akademik</h4>
                        @if ($mahasiswa->pembimbingAkademik)
                            <p class="font-semibold">{{ $mahasiswa->pembimbingAkademik->Nama }}, {{ $mahasiswa->pembimbingAkademik->Gelar }}</p>
                        @else
                            <p class="italic">Belum ditentukan</p>
                        @endif
                        <button @click="showUpdatePaModal = true" class="btn btn-indigo">Update PA</button>
                    </div>

                    <!-- MODALS -->
                    @include('dashboard.partials.modals')

                </div>

                <!-- ========== KOLOM KANAN ========== -->
                <div class="md:col-span-2 space-y-6">
                    
                    <!-- Form Edit Profil -->
                    @include('dashboard.partials.edit-profile-form')

                    <!-- Box Perkembangan Nilai -->
                    <div class="card card-content">
                        <h4 class="card-header">Perkembangan Nilai Akademik</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
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

            <!-- Tabel Histori Keuangan -->
            <div class="mt-6 card card-content">
                <h4 class="card-header">Histori Keuangan Mahasiswa</h4>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Semester</th>
                                <th>Tahun Akademik</th>
                                <th>Jumlah Tagihan</th>
                                <th>Potongan</th>
                                <th>Terbayar</th>
                                <th>Sisa Tagihan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatNilai as $khs)
                                @php
                                    $sisaTagihan = $khs->Biaya - $khs->Potongan - $khs->Bayar;
                                @endphp
                                <tr>
                                    <td>{{ $khs->Sesi }}</td>
                                    <td>{{ $khs->TahunID }}</td>
                                    <td>Rp. {{ number_format($khs->Biaya, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($khs->Potongan, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($khs->Bayar, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($sisaTagihan, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($sisaTagihan <= 0)
                                            <span class="status-badge status-badge-lunas">Lunas</span>
                                        @else
                                            <span class="status-badge status-badge-belum-lunas">Belum Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-gray-500">
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
</x-app-layout>
