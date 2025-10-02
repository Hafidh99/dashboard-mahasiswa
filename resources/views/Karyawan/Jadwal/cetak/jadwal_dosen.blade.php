@extends('layouts.cetak-layout')

@section('title', 'Cetak Jadwal Mengajar Dosen')

@push('styles')
<style>
    @page {
        size: portrait; 
        margin: 20mm;
    }
    .page-break {
        page-break-after: always; 
    }
    .container {
        max-width: 1024px;
    }
</style>
@endpush

@section('content')
    @forelse ($jadwalPerDosen as $dosenId => $jadwals)
        <div class="page-break">
            {{-- KOP SURAT --}}
            <div class="text-center border-b-2 border-black pb-2 mb-4">
                <p class="text-sm font-bold">YAYASAN HANG TUAH PEKANBARU</p>
                <h1 class="text-lg font-bold uppercase">UNIVERSITAS HANG TUAH PEKANBARU</h1>
                <p class="text-xs">Jl. Mustafa Sari No.5 Tangkerang Selatan, Pekanbaru</p>
                <p class="text-xs">Telp. (0761) 7875378, Fax. (0761) 8408780, Website: http://www.htp.ac.id, Email: rektorat@htp.ac.id</p>
            </div>

            <h2 class="text-center font-bold text-md mb-2">Jadwal Mengajar Dosen - Semester {{ substr($tahun, 4, 1) == '1' ? 'Ganjil' : 'Genap' }} {{ substr($tahun, 0, 4) }}/{{ substr($tahun, 0, 4) + 1 }}</h2>
            <p class="text-center font-bold text-md mb-4">{{ $jadwals->first()->NamaDosen }}, {{ $jadwals->first()->Gelar }}</p>

            {{-- TABEL JADWAL --}}
            @foreach ($jadwals->groupBy('ProgramID') as $programId => $jadwalProgram)
                <div class="mb-4">
                    <p class="font-bold bg-gray-200 p-1 border border-black">{{ $programId }}</p>
                    <table class="w-full border border-black text-xs">
                        <thead class="font-bold">
                            <tr class="border-b border-black">
                                <th class="p-1 border border-black">No</th>
                                <th class="p-1 border border-black">Hari</th>
                                <th class="p-1 border border-black">Jam</th>
                                <th class="p-1 border border-black">Kode MK</th>
                                <th class="p-1 border border-black text-left">Matakuliah</th>
                                <th class="p-1 border border-black">SKS</th>
                                <th class="p-1 border border-black">Kelas</th>
                                <th class="p-1 border border-black">Ruang</th>
                                <th class="p-1 border border-black">UAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwalProgram as $item)
                                <tr class="border-b border-gray-300">
                                    <td class="p-1 text-center border border-black">{{ $loop->iteration }}</td>
                                    <td class="p-1 border border-black">{{ $hari[$item->HariID] ?? '' }}</td>
                                    <td class="p-1 text-center border border-black">{{ substr($item->JamMulai, 0, 5) }} - {{ substr($item->JamSelesai, 0, 5) }}</td>
                                    <td class="p-1 border border-black">{{ $item->MKKode }}</td>
                                    <td class="p-1 border border-black">{{ $item->NamaMK }}</td>
                                    <td class="p-1 text-center border border-black">{{ $item->SKS }}</td>
                                    <td class="p-1 text-center border border-black">{{ $item->NamaKelas }}</td>
                                    <td class="p-1 text-center border border-black">{{ $item->RuangID }}</td>
                                    <td class="p-1 border border-black"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach

            {{-- TANDA TANGAN --}}
            <div class="mt-8 flex justify-end">
                <div class="text-center text-xs">
                    <p>Pekanbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p>Ka. BAAK</p>
                    <div class="h-16"></div>
                    <p class="font-bold">Yulanda, S.Kom, M.Kom</p>
                    <p>NIP. 1020067902</p>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center font-bold">Tidak ada data jadwal dosen untuk dicetak sesuai filter yang dipilih.</p>
    @endforelse
@endsection
