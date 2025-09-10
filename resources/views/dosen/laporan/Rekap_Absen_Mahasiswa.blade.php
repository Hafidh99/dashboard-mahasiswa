<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<title>Rekapitulasi Absensi Kuliah</title>
<style>
body { font-family: sans-serif; margin: 20px; font-size: 12px; }
.container { width: 100%; margin: 0 auto; }
.header-table { width: 100%; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px;}
.header-table td { vertical-align: middle; }
.header-text { text-align: center; }
.header-text h3, .header-text h4 { margin: 0; }
.content-table { border-collapse: collapse; margin: 25px 0; font-size: 0.8em; width: 100%; }
.content-table th, .content-table td { border: 1px solid #000; padding: 5px 6px; }
.content-table thead th { background-color: #f2f2f2; text-align: center; }
.info-table { width: 50%; margin-bottom: 20px; font-size: 0.9em; }
.info-table td { padding: 2px 0; }
.footer { margin-top: 40px; font-size: 0.9em; }
</style>
</head>
<body>

<div class="container">
<table class="header-table">
<tr>
<td style="width: 15%;">
<img src="{{ public_path('storage/UHTP.PNG') }}" alt="Logo" style="width: 80px;">
</td>
<td style="width: 85%;" class="header-text">
<h3>UNIVERSITAS HANG TUAH PEKANBARU</h3>
<h4>REKAPITULASI ABSENSI KULIAH</h4>
</td>
</tr>
</table>

<table class="info-table">
    <tr>
        <td width="30%">Mata Kuliah</td>
        <td>: {{ $detailJadwal->NamaMataKuliah }}</td>
    </tr>
    <tr>
        <td>Program Studi</td>
        <td>: {{ $detailJadwal->NamaProdi }}</td>
    </tr>
    <tr>
        <td>Kelas/Tahun Akd</td>
        <td>: {{ $detailJadwal->NamaKelas }}/{{ $detailJadwal->TahunID }}</td>
    </tr>
    <tr>
        <td>Dosen</td>
        <td>: {{ $detailJadwal->NamaDosen }}, {{ $detailJadwal->Gelar }}</td>
    </tr>
</table>

<table class="content-table">
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">NIM</th>
            <th rowspan="2">Nama Mahasiswa</th>
            <th colspan="{{ $pertemuan->count() > 0 ? $pertemuan->count() : 1 }}">Pertemuan Ke</th>
            <th rowspan="2">Jml %</th>
        </tr>
        <tr>
            @foreach($pertemuan as $p)
            <th>{{ $p->Pertemuan }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($mahasiswa as $mhs)
        <tr>
            <td style="text-align: center;">{{ $loop->iteration }}</td>
            <td>{{ $mhs->MhswID }}</td>
            <td>{{ $mhs->Nama }}</td>
            @php $hadirCount = 0; @endphp
            @foreach($pertemuan as $p)
                @php
                    $absenKey = $mhs->MhswID . '_' . $p->PresensiID;
                    $status = isset($absensi[$absenKey]) ? $absensi[$absenKey]->JenisPresensiID : '-';
                    if ($status == 'H') $hadirCount++;
                @endphp
                <td style="text-align: center;">{{ $status }}</td>
            @endforeach
            @if($pertemuan->isEmpty())
                <td>-</td>
            @endif
            @php
                $totalPertemuan = $pertemuan->count();
                $persentase = ($totalPertemuan > 0) ? ($hadirCount / $totalPertemuan) * 100 : 0;
            @endphp
            <td style="text-align: center;">{{ number_format($persentase, 2) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="{{ 4 + ($pertemuan->count() > 0 ? $pertemuan->count() : 1) }}" style="text-align: center;">Tidak ada data mahasiswa di kelas ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    <p>Pekanbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    <br>
    <p>Dosen Pengampuh,</p>
    <br><br><br>
    <p><strong>{{ $detailJadwal->NamaDosen }}, {{ $detailJadwal->Gelar }}</strong></p>
</div>

</div>

</body>
</html>