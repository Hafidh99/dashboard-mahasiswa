@extends('layouts.cetak-layout')

@section('title', 'Presensi Ujian Akhir Semester')

@section('content')
    {{-- KOP SURAT --}}
    <div class="text-center border-b-4 border-black pb-2 mb-4">
        <img src="{{ asset('storage/UHTP.png') }}" alt="Logo Universitas" class="mx-auto mb-2" style="width: 80px;">
        <h1 class="text-xl font-bold uppercase">UNIVERSITAS HANG TUAH PEKANBARU</h1>
        <p class="text-sm">Jl. Mustafa Sari No.5 Tangkerang Selatan</p>
        <p class="text-sm">Telp. +62761 33810, Fax. +62254387800</p>
    </div>

    <h2 class="text-center font-bold text-lg mb-6 uppercase">PRESENSI UJIAN AKHIR SEMESTER</h2>

    {{-- INFORMASI DETAIL JADWAL --}}
    <div class="grid grid-cols-2 gap-x-8 text-sm mb-4">
        {{-- Kolom Kiri --}}
        <div>
            <table class="w-full header-table">
                <tr>
                    <td class="w-1/3 font-semibold">Mata Kuliah</td>
                    <td>: {{ $jadwal->MKKode }} - {{ $jadwal->Nama }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Dosen Pengasuh</td>
                    <td class="align-top">:
                        @if($timDosen->isNotEmpty())
                            @foreach($timDosen as $dosen)
                                {{ $dosen->Nama }}, {{ $dosen->Gelar }}
                                @if(!$loop->last)<br>@endif
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold">Program Studi</td>
                    <td>: {{ $jadwal->prodi->Nama ?? '' }} ({{ $jadwal->ProgramID }})</td>
                </tr>
                <tr>
                    <td class="font-semibold">Kelas / Thn Akd.</td>
                    <td>: {{ $jadwal->kelas->Nama ?? '' }} / SEMESTER {{ strtoupper(\Carbon\Carbon::parse($jadwal->TglMulai)->month > 6 ? 'GANJIL' : 'GENAP') }} {{ $jadwal->TahunID }}</td>
                </tr>
            </table>
        </div>
        {{-- Kolom Kanan --}}
        <div>
            <table class="w-full header-table">
                <tr>
                    <td class="w-1/3 font-semibold">Semester / SKS</td>
                    <td>: {{ optional($jadwal->mk)->Sesi ?? '?' }} / {{ $jadwal->SKS }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Hari / Tgl Ujian</td>
                    <td>: 
                        @php
                            $hariMap = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                        @endphp
                        {{ $hariMap[$jadwal->HariID] ?? '' }} / 
                        {{ $jadwal->UASTanggal ? \Carbon\Carbon::parse($jadwal->UASTanggal)->format('d-m-Y') : '.........................' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- TABEL MAHASISWA --}}
    <table class="w-full border-collapse border border-black text-sm">
        <thead class="bg-gray-200 font-bold">
            <tr class="border-b border-black">
                <th class="p-2 border border-black text-left">Nama Mahasiswa</th>
                <th class="p-2 border border-black">N I M</th>
                <th class="p-2 border border-black">No. Kursi</th>
                <th class="p-2 border border-black">TTD</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswa as $index => $mhs)
                <tr>
                    <td class="p-1 border border-black pl-2">{{ $mhs->NamaMahasiswa }}</td>
                    <td class="p-1 border border-black text-center">{{ $mhs->NIM }}</td>
                    <td class="p-1 border border-black text-center font-bold">{{ $index + 1 }}</td>
                    <td class="p-1 border border-black"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center">Belum ada mahasiswa yang terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
