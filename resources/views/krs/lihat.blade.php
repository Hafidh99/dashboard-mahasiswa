<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print:hidden">
            {{ __('Kartu Rencana Studi (KRS) Aktif') }}
        </h2>
    </x-slot>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .print\:hidden {
                display: none;
            }
            @page {
                size: A4;
                margin: 2cm;
            }
            #print-area table {
                width: 100%;
                border-collapse: collapse;
            }
            #print-area th,
            #print-area td {
                border: 1px solid black;
                padding: 3px 6px;
                font-size: 9px; 
                text-align: left;
            }
            #print-area th:nth-child(1), #print-area td:nth-child(1) { width: 3%; text-align: center; } /* No */
            #print-area th:nth-child(2), #print-area td:nth-child(2) { width: 8%; } /* Kode MK */
            #print-area th:nth-child(3), #print-area td:nth-child(3) { width: 28%; } /* Matakuliah */
            #print-area th:nth-child(4), #print-area td:nth-child(4) { width: 4%; text-align: center; } /* SKS */
            #print-area th:nth-child(5), #print-area td:nth-child(5) { width: 25%; } /* Dosen */
            #print-area th:nth-child(6), #print-area td:nth-child(6) { width: 8%; text-align: center; } /* Ruang */
            #print-area th:nth-child(7), #print-area td:nth-child(7) { width: 8%; text-align: center; } /* Hari */
            #print-area th:nth-child(8), #print-area td:nth-child(8) { width: 16%; text-align: center; } /* Jam */
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div id="print-area">
                        @if ($tahunAktif && $krsAktif->isNotEmpty())
                            <div class="hidden print:block mb-8">
                                <div class="flex items-center justify-center border-b-4 border-black pb-4">
                                    <img src="{{ asset('storage/UHTP.png') }}" alt="Logo Universitas" class="h-24 mr-6">
                                    <div class="text-center">
                                        <p class="text-xl font-bold">UNIVERSITAS HANG TUAH PEKANBARU</p>
                                        <p class="text-sm">Jl. Mustafa Sari No. 05 Tangkerang Selatan, Telp. (0761) 77015, Fax. (0761) 861646</p>
                                        <p class="text-sm">Email: universitas@htp.ac.id atau Pendaftaran: 22INFO-2002 Website: http://www.htp.ac.id</p>
                                    </div>
                                </div>
                                <h3 class="text-center font-bold text-lg mt-6 mb-4 underline">KARTU RENCANA STUDI</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 mb-6 text-sm">
                                <div class="space-y-1">
                                    <div class="flex"><p class="w-32 text-gray-500 print:text-black">Nama Mahasiswa</p><p>: {{ $mahasiswa->Nama }}</p></div>
                                    <div class="flex"><p class="w-32 text-gray-500 print:text-black">NIM</p><p>: {{ $mahasiswa->MhswID }}</p></div>
                                    <div class="flex"><p class="w-32 text-gray-500 print:text-black">Penasehat Akademik</p><p>: {{ $mahasiswa->pembimbingAkademik ? ($mahasiswa->pembimbingAkademik->Nama . ', ' . $mahasiswa->pembimbingAkademik->Gelar) : '-' }}</p></div>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex"><p class="w-32 text-gray-500 print:text-black">Program Studi</p><p>: {{ $mahasiswa->prodi->Nama ?? '-' }}</p></div>
                                    <div class="flex"><p class="w-32 text-gray-500 print:text-black">Tahun Akademik</p><p>: {{ $tahunAktif->Nama ?? '-' }}</p></div>
                                </div>
                            </div>

                            <div class="overflow-x-auto border border-gray-200 rounded-lg print:border-none print:rounded-none">
                                <table class="min-w-full divide-y divide-gray-200 print:divide-none">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode MK</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matakuliah</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pengampu</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ruang</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Kuliah</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status KRS</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider print:hidden">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 print:divide-none">
                                        @php $hari = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']; @endphp
                                        @foreach ($krsAktif as $krs)
                                            <tr>
                                                <td class="px-6 py-4 text-center text-sm">{{ $loop->iteration }}</td>
                                                <td class="px-6 py-4 text-sm">{{ $krs->MKKode }}</td>
                                                <td class="px-6 py-4 text-sm font-medium">{{ $krs->Nama }}</td>
                                                <td class="px-6 py-4 text-center text-sm">{{ $krs->SKS }}</td>
                                                <td class="px-6 py-4 text-center text-sm">{{ $krs->Kelas }}</td>
                                                <td class="px-6 py-4 text-sm">{{ $krs->NamaDosen ? $krs->NamaDosen . ', ' . $krs->Gelar : '-' }}</td>
                                                <td class="px-6 py-4 text-center text-sm">{{ $krs->RuangID }}</td>
                                                <td class="px-6 py-4 text-center text-sm">{{ $hari[$krs->HariID] ?? '-' }}</td>
                                                <td class="px-6 py-4 text-center text-sm">{{ \Carbon\Carbon::parse($krs->JamMulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($krs->JamSelesai)->format('H:i') }}</td>
                                                <td class="px-6 py-4 text-center text-sm">
                                                    @if($krs->aprv_pa == 'Y') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                                    @elseif($krs->aprv_pa == 'N') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                                    @else <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-center text-sm print:hidden">
                                                    @if(!$krsDisetujui)
                                                        <form method="POST" action="{{ route('krs.hapus', $krs->KRSID) }}" onsubmit="return confirm('Anda yakin ingin menghapus mata kuliah ini?');">
                                                            @csrf
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                        </form>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50 print:border print:border-black">
                                        <tr>
                                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-600">Total SKS</td>
                                            <td class="px-6 py-3 text-center text-sm font-bold text-gray-800">{{ $totalSks }}</td>
                                            <td colspan="7" class="print:hidden"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="hidden print:block mt-16">
                                <div class="flex justify-between">
                                    <div class="text-center">
                                        <p>Mengetahui,</p>
                                        <p>Dosen Pembimbing Akademik</p>
                                        <div class="h-20"></div>
                                        <p class="font-bold underline">({{ $mahasiswa->pembimbingAkademik->Nama ?? '____________________' }})</p>
                                        <p>NIDN. {{ $mahasiswa->pembimbingAkademik->NIDN ?? '____________________' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p>Pekanbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                        <p>Mahasiswa ybs,</p>
                                        <div class="h-20"></div>
                                        <p class="font-bold underline">({{ $mahasiswa->Nama }})</p>
                                        <p>NIM. {{ $mahasiswa->MhswID }}</p>
                                    </div>
                                </div>
                            </div>

                        @else
                            <div class="text-center py-16">
                                <p class="text-gray-500 mb-4">Anda belum melakukan pengisian KRS untuk semester ini.</p>
                                <a href="{{ route('krs.isi') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Isi KRS Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3 print:hidden">
                        @if ($krsAktif->isNotEmpty())
                            @if(!$krsDisetujui)
                                <a href="{{ route('krs.ambil') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Tambah MK
                                </a>
                            @endif
                            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cetak KRS
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
