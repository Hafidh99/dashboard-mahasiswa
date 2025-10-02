@extends('layouts.cetak-layout')

@section('title', 'Cetak Jadwal Kuliah Per Ruang')

@push('styles')
<style>
    @page {
        size: portrait;
        margin: 20mm;
    }
    .container {
        max-width: 1024px;
    }
</style>
@endpush

@section('content')
    {{-- KOP SURAT --}}
    <div class="text-center border-b-4 border-black pb-2 mb-4">
        <img src="{{ asset('storage/UHTP.png') }}" alt="Logo Universitas" class="mx-auto mb-2" style="width: 80px;">
        <h1 class="text-xl font-bold uppercase">UNIVERSITAS HANG TUAH PEKANBARU</h1>
        <p class="text-sm">Jl. Mustafa Sari No.5 Tangkerang Selatan, Pekanbaru</p>
        <p class="text-sm">Telp. (0761) 7875378, Fax. (0761) 8408780</p>
    </div>

    <h2 class="text-center font-bold text-lg mb-4">
        Jadwal Kuliah per Ruang - Semester {{ substr($tahun, 4, 1) == '1' ? 'Ganjil' : 'Genap' }} {{ substr($tahun, 0, 4) }}/{{ substr($tahun, 0, 4) + 1 }}
    </h2>

    @forelse ($jadwalPerRuang as $ruangId => $jadwals)
        <div class="mb-6">
            <p class="font-bold">Ruang: {{ $ruangId }}</p>
            <p class="text-sm mb-1">Kampus: </p>
            <table class="w-full border border-black text-xs">
                <thead class="bg-gray-200 font-bold">
                    <tr class="border-b border-black">
                        <th class="p-1 border border-black">No</th>
                        <th class="p-1 border border-black">Hari</th>
                        <th class="p-1 border border-black">Jam</th>
                        <th class="p-1 border border-black">Kode MK</th>
                        <th class="p-1 border border-black text-left">Matakuliah</th>
                        <th class="p-1 border border-black">SKS</th>
                        <th class="p-1 border border-black">Kelas</th>
                        <th class="p-1 border border-black">UAS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwals as $item)
                        <tr class="border-b border-gray-300">
                            <td class="p-1 text-center border border-black">{{ $loop->iteration }}</td>
                            <td class="p-1 border border-black">{{ $hari[$item->HariID] ?? '' }}</td>
                            <td class="p-1 text-center border border-black">{{ substr($item->JamMulai, 0, 5) }} - {{ substr($item->JamSelesai, 0, 5) }}</td>
                            <td class="p-1 border border-black">{{ $item->MKKode }}</td>
                            <td class="p-1 border border-black">{{ $item->NamaMK }}</td>
                            <td class="p-1 text-center border border-black">{{ $item->SKS }}</td>
                            <td class="p-1 text-center border border-black">{{ $item->NamaKelas }}</td>
                            <td class="p-1 border border-black"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p class="text-center font-bold">Tidak ada data jadwal untuk dicetak sesuai filter yang dipilih.</p>
    @endforelse
@endsection
