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

@extends('layouts.cetak-layout')

@section('title', 'Cetak Jadwal Kuliah')

@section('content')
    {{-- KOP SURAT --}}
    <div class="text-center border-b-4 border-black pb-2 mb-4">
        <img src="{{ asset('storage/UHTP.png') }}" alt="Logo Universitas" class="mx-auto mb-2" style="width: 80px;">
        <h1 class="text-xl font-bold uppercase">UNIVERSITAS HANG TUAH PEKANBARU</h1>
        <p class="text-sm">Jl. Mustafa Sari No.5 Tangkerang Selatan, Pekanbaru</p>
        <p class="text-sm">Telp. (0761) 7875378, Fax. (0761) 8408780</p>
    </div>

    <h2 class="text-center font-bold text-lg mb-4">JADWAL KULIAH</h2>

    {{-- INFORMASI FILTER --}}
    <table class="w-full text-sm mb-4">
        <tbody>
            <tr>
                <td class="font-bold p-1">Thn Akd.</td>
                <td class="p-1">: {{ $tahun->TahunID ?? '-' }}</td>
                <td class="font-bold p-1">Prg Studi</td>
                <td class="p-1">: {{ $prodi->Nama ?? 'Semua' }}</td>
            </tr>
            <tr>
                <td class="font-bold p-1">Hari</td>
                <td class="p-1">: {{ !empty($input['hari_id']) ? $hari[$input['hari_id']] : 'Semua' }}</td>
                <td class="font-bold p-1">Prg Pendidikan</td>
                <td class="p-1">: {{ $program->Nama ?? 'Semua' }}</td>
            </tr>
            <tr>
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
                <th class="p-2 border border-black">No</th>
                <th class="p-2 border border-black">Hari</th>
                <th class="p-2 border border-black">Jam</th>
                <th class="p-2 border border-black">Kode MK</th>
                <th class="p-2 border border-black text-left">Matakuliah</th>
                <th class="p-2 border border-black">SKS</th>
                <th class="p-2 border border-black text-left">Dosen Pengasuh</th>
                <th class="p-2 border border-black">Kelas</th>
                <th class="p-2 border border-black">Ruangan</th>
                <th class="p-2 border border-black">Mhs</th>
            </tr>
        </thead>
        <tbody>
            @php $nomor = 1; @endphp
            @forelse ($jadwalDikelompokkan as $hariId => $jadwals)
                @foreach ($jadwals as $jadwal)
                    <tr class="border-b border-gray-300">
                        <td class="p-1 text-center border border-black">{{ $nomor++ }}</td>
                        <td class="p-1 border border-black">{{ $hari[$hariId] }}</td>
                        <td class="p-1 text-center border border-black">{{ substr($jadwal->JamMulai, 0, 5) }} - {{ substr($jadwal->JamSelesai, 0, 5) }}</td>
                        <td class="p-1 border border-black">{{ $jadwal->MKKode }}</td>
                        <td class="p-1 border border-black">{{ $jadwal->Nama }}</td>
                        <td class="p-1 text-center border border-black">{{ $jadwal->SKS }}</td>
                        <td class="p-1 border border-black">{{ $jadwal->NamaDosen }}, {{ $jadwal->Gelar }}</td>
                        <td class="p-1 text-center border border-black">{{ $jadwal->NamaKelas }}</td>
                        <td class="p-1 text-center border border-black">{{ $jadwal->RuangID }}</td>
                        <td class="p-1 text-center border border-black">{{ $jadwal->Mhs }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="10" class="p-4 text-center">Tidak ada data jadwal untuk dicetak.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="mt-8 flex justify-end">
        <div class="text-center">
            <p>Pekanbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <div class="h-20"></div> 
            <p class="font-bold">(_________________________)</p>
        </div>
    </div>
@endsection

