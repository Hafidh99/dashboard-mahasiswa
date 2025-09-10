<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<title>Detail Nilai Mata Kuliah Mahasiswa</title>
<style>
body { font-family: sans-serif; margin: 15px; font-size: 10px; }
.container { width: 100%; margin: 0 auto; }
.header-table { width: 100%; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px; }
.header-table .logo { width: 80px; }
.header-table .kop { text-align: center; }
.header-table h3, .header-table h4 { margin: 0; }
.content-table { border-collapse: collapse; margin: 25px 0; font-size: 0.9em; width: 100%; }
.content-table th, .content-table td { border: 1px solid #000; padding: 4px 5px; text-align: center; }
.content-table .nama-mahasiswa { text-align: left; }
.content-table thead th { background-color: #f2f2f2; }
.info-table { width: 60%; margin-bottom: 20px; font-size: 0.9em; }
.info-table td { padding: 2px 0; }
.footer { margin-top: 40px; font-size: 0.9em; }
</style>
</head>
<body>
<div class="container">
<table class="header-table">
<tr>
<td class="logo"><img src="{{ public_path('storage/UHTP.PNG') }}" alt="Logo" width="80"></td>
<td class="kop">
<h3>UNIVERSITAS HANG TUAH PEKANBARU</h3>
<h4>Detail Nilai Mata Kuliah Mahasiswa</h4>
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
            <td>: {{ $detailJadwal->NamaKelas }} / {{ $detailJadwal->TahunID }}</td>
        </tr>
        <tr>
            <td>Dosen</td>
            <td>: {{ $detailJadwal->NamaDosen }}, {{ $detailJadwal->Gelar }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Kehadiran</th>
                <th>Tugas 1</th>
                <th>Tugas 2</th>
                <th>Tugas 3</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Nilai Akhir</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswa as $mhs)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="nama-mahasiswa">{{ $mhs->MhswID }}</td>
                <td class="nama-mahasiswa">{{ $mhs->Nama }}</td>
                <td>{{ $mhs->Presensi }}</td>
                <td>{{ $mhs->Tugas1 }}</td>
                <td>{{ $mhs->Tugas2 }}</td>
                <td>{{ $mhs->Tugas3 }}</td>
                <td>{{ $mhs->UTS }}</td>
                <td>{{ $mhs->UAS }}</td>
                <td>{{ $mhs->NilaiAkhir }}</td>
                <td>{{ $mhs->Grade }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center;">Tidak ada data nilai mahasiswa.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p style="margin-left: 70%;">Pekanbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <br>
        <p style="margin-left: 70%;">Dosen Pengampuh,</p>
        <br><br><br>
        <p style="margin-left: 70%;"><strong>{{ $detailJadwal->NamaDosen }}, {{ $detailJadwal->Gelar }}</strong></p>
    </div>
</div>

</body>
</html>