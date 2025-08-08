<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Isi Kartu Rencana Studi (KRS)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($tahunAktif)
                <div class="info-box info-box-blue">
                    <p class="font-bold">Info</p>
                    <p>
                        Pengisian KRS untuk {{ $tahunAktif->Nama ?? 'Tahun Akademik' }}
                        @if($tahunAktif->TglKRSMulai && $tahunAktif->TglKRSMulai != '0000-00-00')
                            dibuka dari tanggal <strong>{{ \Carbon\Carbon::parse($tahunAktif->TglKRSMulai)->format('d F Y') }}</strong> sampai <strong>{{ \Carbon\Carbon::parse($tahunAktif->TglKRSSelesai)->format('d F Y') }}</strong>.
                        @else
                            (Jadwal belum ditentukan).
                        @endif
                    </p>
                </div>

                {{-- Tampilkan notifikasi berdasarkan status --}}
                @if (!$keuanganLunas)
                    <div class="info-box info-box-red">
                        <p class="font-bold">Perhatian: Administrasi Keuangan</p>
                        <p>Anda tidak dapat mengisi KRS karena masih memiliki sisa tagihan sebesar <strong>Rp. {{ number_format($sisaTagihan, 0, ',', '.') }}</strong>. Harap selesaikan administrasi keuangan Anda terlebih dahulu.</p>
                    </div>
                @elseif ($sudahIsiKrs)
                    <div class="info-box info-box-green">
                        <p class="font-bold">Anda Sudah Melakukan Pengisian KRS Untuk Tahun Akademik: {{ $tahunAktif->TahunID }}</p>
                    </div>
                @elseif ($bisaIsiKrs)
                    <div class="card card-content">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">
                            Ambil Mata Kuliah - Tahun Akademik {{ $tahunAktif->TahunID }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Silakan klik tombol di bawah ini untuk memulai pengambilan mata kuliah Anda.
                        </p>
                        <form method="POST" action="{{ route('krs.ambil') }}">
                            @csrf
                            <button type="submit" class="btn btn-blue w-auto mt-0">Ambil Mata Kuliah</button>
                        </form>
                    </div>
                @else
                    <div class="info-box info-box-red">
                        <p class="font-bold">Maaf, saat ini bukan jadwal pengisian KRS.</p>
                    </div>
                @endif

            @else
                <div class="info-box info-box-gray">
                    <p class="font-bold">Saat ini belum ada jadwal pengisian KRS yang aktif untuk program studi Anda.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
