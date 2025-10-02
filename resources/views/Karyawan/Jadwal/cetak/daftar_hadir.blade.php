@extends('layouts.cetak-layout')

@section('title', 'Daftar Lengkap Mahasiswa dan Nilai')

@push('styles')
<style>
    @page {
        size: landscape;
        margin: 15mm;
    }
    .container {
        max-width: 1280px;
    }
    .header-table td {
        padding: 1px 4px;
        vertical-align: top;
    }
</style>
@endpush

@section('content')
    @php
        $semesterCode = substr($jadwal->TahunID, 4, 1);
        $tahunAwal = substr($jadwal->TahunID, 0, 4);
        
        $semesterNama = ($semesterCode == '1') ? 'GANJIL' : 'GENAP';
        $tahunAkademikFormatted = "SEMESTER $semesterNama $tahunAwal";
    @endphp

    {{-- KOP SURAT UTAMA --}}
    <div class="flex justify-between items-start border-b-4 border-black pb-2 mb-2">
        <div class="flex items-center">
            <img src="{{ asset('storage/UHTP.png') }}" alt="Logo Universitas" style="width: 80px;" class="mr-4">
            <div>
                <h1 class="text-xl font-bold uppercase">UNIVERSITAS HANG TUAH PEKANBARU</h1>
                <p class="text-sm">Jl. Mustafa Sari No.5 Tangkerang Selatan, Pekanbaru</p>
                <p class="text-sm">Telp. (0761) 7875378, Fax. (0761) 8408780</p>
            </div>
        </div>
    </div>

    <h2 class="text-center font-bold text-lg my-2 uppercase">Daftar Lengkap Siswa dan Nilai</h2>

    {{-- INFORMASI DETAIL --}}
    <div class="grid grid-cols-2 gap-x-8 text-sm mb-2">
        {{-- Kolom Kiri --}}
        <div>
            <table class="w-full header-table">
                <tr>
                    <td class="w-1/3 font-semibold">Kode Mata Kuliah</td>
                    <td>: {{ $jadwal->MKKode }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Mata Kuliah</td>
                    <td>: {{ $jadwal->Nama }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Kelas</td>
                    <td>: {{ $jadwal->kelas->Nama ?? '' }} ({{ $jadwal->ProgramID }})</td>
                </tr>
                <tr>
                    <td class="font-semibold">Tahun Akademik</td>
                    <td>: {{ $tahunAkademikFormatted }}</td>
                </tr>
            </table>
        </div>
        {{-- Kolom Kanan --}}
        <div>
            <div class="text-right font-bold text-sm">
                <p>YAYASAN HANG TUAH PEKANBARU</p>
                <p>UNIVERSITAS HANG TUAH PEKANBARU</p>
            </div>
            <table class="w-full header-table mt-1">
                <tr>
                    <td class="w-1/3 font-semibold">Dosen Pengasuh</td>
                    <td>:
                        @forelse($timDosen as $dosen)
                            {{ $dosen->Nama }}, {{ $dosen->Gelar }}<br>
                        @empty
                            -
                        @endforelse
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold">Program Studi</td>
                    <td>: {{ $jadwal->prodi->Nama ?? '' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Kelas / Thn Akd.</td>
                    <td>: {{ $jadwal->kelas->Nama ?? '' }} / {{ $tahunAkademikFormatted }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Semester / SKS</td>
                    <td>: {{ optional($jadwal->mk)->Sesi ?? '?' }} / {{ $jadwal->SKS }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Hari / Tgl Ujian</td>
                    <td>: 
                        @php
                            $hariMap = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                        @endphp
                        {{ $hariMap[$jadwal->HariID] ?? '' }} / 
                        {{ $jadwal->UASTanggal ? \Carbon\Carbon::parse($jadwal->UASTanggal)->format('d-m-Y') : '-' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- DUA TABEL UTAMA --}}
    <div class="grid grid-cols-2 gap-x-4 text-xs">
        {{-- Tabel Kiri: Nilai --}}
        <div>
            <table class="w-full border-collapse border border-black">
                <thead>
                    <tr class="bg-gray-200">
                        <th rowspan="2" class="p-1 border border-black">NAMA MAHASISWA</th>
                        <th rowspan="2" class="p-1 border border-black">NIM</th>
                        <th rowspan="2" class="p-1 border border-black">TTD MID</th>
                        <th rowspan="2" class="p-1 border border-black">TTD UAS</th>
                        <th colspan="4" class="p-1 border border-black">Nilai</th>
                        <th rowspan="2" class="p-1 border border-black">Jumlah</th>
                        <th rowspan="2" class="p-1 border border-black">NILAI Akhir</th>
                    </tr>
                    <tr class="bg-gray-200">
                        <th class="p-1 border border-black">Aktp 10%</th>
                        <th class="p-1 border border-black">Tugas 20%</th>
                        <th class="p-1 border border-black">MID 30%</th>
                        <th class="p-1 border border-black">UAS 40%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $mhs)
                        <tr>
                            <td class="p-1 border border-black">{{ $mhs->NamaMahasiswa }}</td>
                            <td class="p-1 border border-black text-center">{{ $mhs->NIM }}</td>
                            <td class="p-1 border border-black">...........</td>
                            <td class="p-1 border border-black">...........</td>
                            <td class="p-1 border border-black">...........</td>
                            <td class="p-1 border border-black">...........</td>
                            <td class="p-1 border border-black">...........</td>
                            <td class="p-1 border border-black">...........</td>
                            <td class="p-1 border border-black">...........</td>
                            <td class="p-1 border border-black">...........</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center p-2 border border-black">Belum ada mahasiswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabel Kanan: Kehadiran --}}
        <div>
            <table class="w-full border-collapse border border-black">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-1 border border-black">No. Urut</th>
                        <th class="p-1 border border-black">NIM</th>
                        <th class="p-1 border border-black">NAMA</th>
                        <th class="p-1 border border-black">TTD UAS</th>
                        <th class="p-1 border border-black">Nilai Akhir</th>
                        <th class="p-1 border border-black">Ket.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $index => $mhs)
                        <tr>
                            <td class="p-1 border border-black text-center">{{ $index + 1 }}</td>
                            <td class="p-1 border border-black text-center">{{ $mhs->NIM }}</td>
                            <td class="p-1 border border-black">{{ $mhs->NamaMahasiswa }}</td>
                            <td class="p-1 border border-black">...........</td>
                            <td class="p-1 border border-black">...........</td>
                            <td class="p-1 border border-black">...........</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-2 border border-black">-</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection