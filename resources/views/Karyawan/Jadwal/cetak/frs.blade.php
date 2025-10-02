@extends('layouts.cetak-layout')

@section('title', 'Cetak Formulir Rencana Studi')

@push('styles')
<style>
    @page {
        size: landscape;
        margin: 20mm;
    }
    .container {
        max-width: 1280px; 
    }
</style>
@endpush

@section('content')
    {{-- KOP SURAT --}}
    <div class="flex items-center justify-between border-b-4 border-black pb-2 mb-4">
        <div class="flex items-center">
            <img src="{{ asset('storage/UHTP.png') }}" alt="Logo Universitas" class="mr-4" style="width: 80px;">
            <div>
                <h1 class="text-xl font-bold uppercase">UNIVERSITAS HANG TUAH PEKANBARU</h1>
                <p class="text-sm">Jl. Mustafa Sari No.5 Tangkerang Selatan, Pekanbaru</p>
                <p class="text-sm">Telp. (0761) 7875378, Fax. (0761) 8408780</p>
            </div>
        </div>
        <h2 class="text-center font-bold text-xl">Formulir Rencana Studi</h2>
    </div>

    {{-- INFORMASI FILTER --}}
    <table class="w-full text-sm mb-4">
        <tbody>
            <tr>
                <td class="font-bold p-1">Thn Akd.</td>
                <td class="p-1">: {{ $tahun->TahunID ?? '-' }}</td>
                <td class="font-bold p-1">Prg Studi</td>
                <td class="p-1">: {{ $prodi->Nama ?? 'Semua' }}</td>
                <td class="font-bold p-1">Prg Pendidikan</td>
                <td class="p-1">: {{ $program->Nama ?? 'Semua' }}</td>
            </tr>
            <tr>
                <td class="font-bold p-1">Hari</td>
                <td class="p-1">: {{ !empty($input['hari_id']) ? $hari[$input['hari_id']] : 'Semua' }}</td>
                <td class="font-bold p-1">Kelas</td>
                <td class="p-1">: {{ !empty($input['kelas_id']) ? (\App\Models\Kelas::find($input['kelas_id'])->Nama ?? 'Semua') : 'Semua' }}</td>
                <td class="font-bold p-1">Semester</td>
                <td class="p-1">: {{ !empty($input['semester_mk']) ? $input['semester_mk'] : 'Semua' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- TABEL JADWAL --}}
    <table class="w-full border border-black text-xs">
        <thead class="bg-gray-200 font-bold">
            <tr class="border-b border-black">
                <th class="p-2 border border-black">Ambil</th>
                <th class="p-2 border border-black">No</th>
                <th class="p-2 border border-black">Kode MK</th>
                <th class="p-2 border border-black text-left">Matakuliah</th>
                <th class="p-2 border border-black">SKS</th>
                <th class="p-2 border border-black text-left">Dosen Pengajar</th>
                <th class="p-2 border border-black">Hari</th>
                <th class="p-2 border border-black">Jam</th>
                <th class="p-2 border border-black">Kelas</th>
                <th class="p-2 border border-black">Ruangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwal as $item)
                <tr class="border-b border-gray-300">
                    <td class="p-1 text-center border border-black"><div class="w-4 h-4 border border-black mx-auto"></div></td>
                    <td class="p-1 text-center border border-black">{{ $loop->iteration }}</td>
                    <td class="p-1 border border-black">{{ $item->MKKode }}</td>
                    <td class="p-1 border border-black">{{ $item->Nama }}</td>
                    <td class="p-1 text-center border border-black">{{ $item->SKS }}</td>
                    <td class="p-1 border border-black">{{ $item->NamaDosen }}, {{ $item->Gelar }}</td>
                    <td class="p-1 border border-black">{{ $hari[$item->HariID] ?? '' }}</td>
                    <td class="p-1 text-center border border-black">{{ substr($item->JamMulai, 0, 5) }} - {{ substr($item->JamSelesai, 0, 5) }}</td>
                    <td class="p-1 text-center border border-black">{{ $item->NamaKelas }}</td>
                    <td class="p-1 text-center border border-black">{{ $item->RuangID }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="p-4 text-center">Tidak ada matakuliah yang ditawarkan untuk filter yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="mt-8 flex justify-between">
        <div class="text-center">
            <p>Pekanbaru, _______________</p>
            <br>
            <p class="font-bold">Siswa</p>
            <div class="h-16"></div> 
            <p>(_________________________)</p>
            <p>NIM: ______________________</p>
        </div>
        <div class="text-center">
            <p>Mengetahui,</p>
            <br>
            <p class="font-bold">Dosen Pembimbing Akademik</p>
            <div class="h-16"></div>
            <p>(_________________________)</p>
            <p>NIDN: _____________________</p>
        </div>
    </div>
@endsection
