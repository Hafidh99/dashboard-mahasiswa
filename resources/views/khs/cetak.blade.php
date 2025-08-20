<x-app-layout>
    {{-- Bagian ini akan disembunyikan saat mencetak --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print:hidden">
            {{ __('Cetak Kartu Hasil Studi (KHS)') }}
        </h2>
    </x-slot>

    {{-- CSS khusus untuk print (dengan perbaikan) --}}
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

            #print-area table {
                width: 100%;
                border-collapse: collapse;
            }
            #print-area th,
            #print-area td {
                border: 1px solid black;
                padding: 3px 6px;
                font-size: 10px;  
                text-align: left;
            }
            #print-area thead {
                background-color: transparent !important;
            }
             #print-area tfoot {
                background-color: transparent !important;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-8 p-4 border border-gray-200 rounded-lg print:hidden">
                        <form method="GET" action="{{ route('khs.cetak') }}" class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                            <div class="flex-1">
                                <label for="semester" class="block text-sm font-medium text-gray-500 mb-1">Pilih Semester</label>
                                <select name="semester" id="semester" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Pilih Semester --</option>
                                    @foreach($semesterList as $sem)
                                        <option value="{{ $sem }}" {{ $selectedSemester == $sem ? 'selected' : '' }}>
                                            Semester {{ $sem }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end space-x-2 mt-4 sm:mt-0">
                                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                    Pilih
                                </button>
                                @if($selectedSemester)
                                <button type="button" onclick="window.print()" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                    Print
                                </button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div id="print-area">
                        @if($selectedSemester && $khsInfo)
                            <div class="hidden print:block mb-8">
                                <div class="flex items-center justify-center border-b-4 border-black pb-4">
                                    <img src="{{ asset('storage/UHTP.png') }}" alt="Logo Universitas" class="h-24 mr-6">
                                    <div class="text-center">
                                        <p class="text-xl font-bold">UNIVERSITAS HANG TUAH PEKANBARU</p>
                                        <p class="text-sm">Jl. Mustafa Sari No. 05 Tangkerang Selatan, Telp. (0761) 77015, Fax. (0761) 861646</p>
                                        <p class="text-sm">Email: universitas@htp.ac.id atau Pendaftaran: 22INFO-2002 Website: http://www.htp.ac.id</p>
                                    </div>
                                </div>
                                <h3 class="text-center font-bold text-lg mt-6 mb-4 underline">KARTU HASIL STUDI</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 mb-6 text-sm">
                                <div class="space-y-1">
                                    <div class="flex"><p class="w-24 text-gray-500 print:w-32 print:font-normal print:text-black">Nama</p><p>: {{ $mahasiswa->Nama }}</p></div>
                                    <div class="flex"><p class="w-24 text-gray-500 print:w-32 print:font-normal print:text-black">NIM</p><p>: {{ $mahasiswa->MhswID }}</p></div>
                                    <div class="flex"><p class="w-24 text-gray-500 print:w-32 print:font-normal print:text-black">Dosen PA</p><p>: {{ $mahasiswa->pembimbingAkademik->Nama ?? '-' }}, {{ $mahasiswa->pembimbingAkademik->Gelar ?? '' }}</p></div>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex"><p class="w-32 text-gray-500 print:font-normal print:text-black">Tahun Akademik</p><p>: {{ $khsInfo->TahunID }}</p></div>
                                    <div class="flex"><p class="w-32 text-gray-500 print:font-normal print:text-black">Program Studi</p><p>: {{ $mahasiswa->prodi->Nama }}</p></div>
                                    <div class="flex"><p class="w-32 text-gray-500 print:font-normal print:text-black">Semester</p><p>: {{ $khsInfo->Sesi }}</p></div>
                                </div>
                            </div>

                            <div class="overflow-x-auto border border-gray-200 rounded-lg print:border-none print:rounded-none">
                                <table class="min-w-full divide-y divide-gray-200 print:divide-none">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode MK</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Mutu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 print:divide-none">
                                        @forelse($krsDetail as $krs)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $krs->MKKode }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $krs->Nama }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $krs->SKS }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $krs->GradeNilai }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ number_format($krs->BobotNilai, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ number_format($krs->Mutu, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                                Data mata kuliah tidak ditemukan untuk semester ini.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-600">Total</td>
                                            <td class="px-6 py-3 text-center text-sm font-medium text-gray-600">{{ $totalSks }}</td>
                                            <td colspan="2"></td>
                                            <td class="px-6 py-3 text-center text-sm font-medium text-gray-600">{{ number_format($totalMutu, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="px-6 py-3 text-right text-sm font-bold text-gray-700">Indeks Prestasi Semester (IPS)</td>
                                            <td class="px-6 py-3 text-center text-sm font-bold text-gray-900">{{ number_format($ips, 2) }}</td>
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
                            <div class="text-center py-8 text-gray-500 print:hidden">
                                <p>Silakan pilih semester untuk menampilkan Kartu Hasil Studi.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
