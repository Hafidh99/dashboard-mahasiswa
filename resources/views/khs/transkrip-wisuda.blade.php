<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transkrip Wisuda - {{ $mahasiswa->Nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f3f4f6; 
        }
        .container {
            width: 21cm;
            min-height: 29.7cm;
            padding: 2cm;
            margin: 1rem auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 2px 5px;
            font-size: 9.5pt; 
            vertical-align: top;
        }
        th {
            font-weight: bold;
            text-align: center;
        }
        .info-table td {
            border: none;
            padding: 0px 4px; 
            font-size: 9.5pt;
        }
        .summary-table td {
            border: 1px solid black;
            padding: 2px 5px;
            font-size: 9.5pt;
        }
        .photo-box {
            width: 2cm;
            height: 3cm;
            border: 1px solid black;
            text-align: center;
            padding-top: 1.2cm;
            font-size: 9pt;
        }

        @media print {
            body {
                margin: 0;
                background-color: white;
            }
            .container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
            }
            @page {
                size: A4;
                margin: 1.5cm; 
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- KOP SURAT -->
        <div class="flex items-start justify-center border-b-2 border-black pb-2 mb-2">
            <img src="{{ asset('storage/UHTP.png') }}" alt="Logo Universitas" class="h-20 mr-4"> 
            <div class="text-center">
                <p class="text-base font-bold">UNIVERSITAS HANG TUAH PEKANBARU</p>
                <p class="text-xs">Jl. Mustafa Sari No. 05 Tangkerang Selatan, Telp. (0761) 33815 Fax. (0761) 863646</p>
                <p class="text-xs">Email: stikes.htp@gmail.com SK. Mendiknas : 226/D/O/2002 Website : www.htp.ac.id</p>
            </div>
        </div>
        <p class="text-xs mb-2">No. Seri Transkrip / Transcript Serial Number: .................................</p>

        <!-- JUDUL -->
        <div class="text-center font-bold mb-3 text-sm">
            <p>PROGRAM STUDI KESEHATAN MASYARAKAT</p>
            <p>PROGRAM SARJANA</p>
            <p>TRANSKRIP NILAI / ACADEMIC TRANSCRIPT</p>
        </div>

        <!-- INFORMASI MAHASISISWA -->
        <div class="flex justify-between items-start mb-3">
            <table class="info-table w-3/4">
                <tr><td class="w-2/5">Nama / Name</td><td>: {{ strtoupper($mahasiswa->Nama) }}</td></tr>
                <tr><td>NIM / Student Registered Number</td><td>: {{ $mahasiswa->MhswID }}</td></tr>
                <tr><td>Tempat dan Tanggal Lahir / Place and Date of Birth</td><td>: {{ strtoupper($mahasiswa->TempatLahir) }}, {{ \Carbon\Carbon::parse($mahasiswa->TanggalLahir)->format('d F Y') }}</td></tr>
                <tr><td>Nomor Induk Kependudukan / National Identity Number</td><td>: .................................</td></tr>
                <tr><td>Nomor Ijazah Nasional / National Certificate Number</td><td>: .................................</td></tr>
                <tr><td>Peminatan / Specials</td><td>: .................................</td></tr>
                <tr><td>Gelar / Title</td><td>: SKM</td></tr>
            </table>
            <div class="photo-box">FOTO 2x3</div>
        </div>

        <!-- TABEL NILAI -->
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">NO</th>
                    <th style="width: 10%;">KODE MK</th>
                    <th style="width: 50%;">MATA KULIAH / NAME OF SUBJECT</th>
                    <th style="width: 5%;">SKS</th>
                    <th style="width: 7%;">NILAI</th>
                    <th style="width: 7%;">BOBOT</th>
                    <th style="width: 7%;">MUTU</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transkripLengkap as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $item->MKKode }}</td>
                    <td>{{ $item->Nama }}</td>
                    <td class="text-center">{{ $item->SKS }}</td>
                    <td class="text-center">{{ $item->GradeNilai }}</td>
                    <td class="text-center">{{ number_format($item->BobotNilai, 2) }}</td>
                    <td class="text-center">{{ number_format($item->Mutu, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SUMMARY AKADEMIK -->
        <table class="summary-table mt-3">
            <tr>
                <td class="w-2/5">Tanggal Masuk / Date Of Entry</td>
                <td>: {{ \Carbon\Carbon::parse($dataWisuda['tanggalMasuk'])->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Tanggal Lulus / Date Of Graduation</td>
                <td>: {{ \Carbon\Carbon::parse($dataWisuda['tanggalLulus'])->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Jumlah SKS yang diperoleh / Number of Credits Earned</td>
                <td>: {{ $totalSks }}</td>
            </tr>
            <tr>
                <td>Indeks Prestasi Kumulatif / GPA</td>
                <td>: {{ number_format($ipk, 2) }}</td>
            </tr>
            <tr>
                <td>Predikat Kelulusan / Predicates Graduation</td>
                <td>: {{ $dataWisuda['predikat'] }}</td>
            </tr>
            <tr>
                <td class="align-top">Judul Skripsi / Title of Undergraduate Thesis</td>
                <td class="align-top">: {{ $dataWisuda['judulSkripsi'] }}</td>
            </tr>
        </table>

        <!-- TANDA TANGAN -->
        <div class="flex justify-between mt-6 text-xs">
            <div class="text-center w-1/2">
                <p>Mengetahui</p>
                <p>Rektor Universitas Hang Tuah Pekanbaru</p>
                <p>Rector of Hang Tuah Pekanbaru University</p>
                <div class="h-20"></div>
                <p class="font-bold">Prof. Dr. . Syafrani, MSi</p>
            </div>
            <div class="text-center w-1/2">
                <p>Pekanbaru, {{ \Carbon\Carbon::parse($dataWisuda['tanggalLulus'])->format('d F Y') }}</p>
                <p>Dekan Fakultas Kesehatan</p>
                <p>Dean Of Health Faculty</p>
                <div class="h-20"></div>
                <p class="font-bold">Ns. Abdurahman Hamid,M.Kep,Sp.Kep.Kom</p>
            </div>
        </div>

    </div>

</body>
</html>
