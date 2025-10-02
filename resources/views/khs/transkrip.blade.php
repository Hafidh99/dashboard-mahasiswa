<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print:hidden">
            {{ __('Cetak Transkrip Nilai') }}
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
                font-size: 10px;  
                text-align: left;
            }
            #print-area th:nth-child(1), #print-area td:nth-child(1) { width: 4%; text-align: center; } /* No */
            #print-area th:nth-child(2), #print-area td:nth-child(2) { width: 10%; } /* Kode MK */
            #print-area th:nth-child(3), #print-area td:nth-child(3) { width: 42%; } /* Nama Mata Kuliah */
            #print-area th:nth-child(4), #print-area td:nth-child(4) { width: 5%; text-align: center; } /* Smt */
            #print-area th:nth-child(5), #print-area td:nth-child(5) { width: 5%; text-align: center; } /* SKS */
            #print-area th:nth-child(6), #print-area td:nth-child(6) { width: 8%; text-align: center; } /* Nilai */
            #print-area th:nth-child(7), #print-area td:nth-child(7) { width: 8%; text-align: center; } /* Bobot */
            #print-area th:nth-child(8), #print-area td:nth-child(8) { width: 8%; text-align: center; } /* Mutu */

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
                        <form method="GET" action="{{ route('khs.transkrip') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="semester_awal" class="block text-sm font-medium text-gray-500 mb-1">Dari Semester</label>
                                <select name="semester_awal" id="semester_awal" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Pilih --</option>
                                    @foreach($semesterList as $sem)
                                        <option value="{{ $sem }}" {{ $semesterAwal == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="semester_akhir" class="block text-sm font-medium text-gray-500 mb-1">Sampai Semester</label>
                                <select name="semester_akhir" id="semester_akhir" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Pilih --</option>
                                    @foreach($semesterList as $sem)
                                        <option value="{{ $sem }}" {{ $semesterAkhir == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                    Pilih
                                </button>
                                @if($semesterAwal && $semesterAkhir)
                                <button type="button" onclick="window.print()" class="w-full inline-flex justify-center items-center px-6 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                    Print
                                </button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div id="print-area">
                        @if($semesterAwal && $semesterAkhir)
                            <div class="hidden print:block mb-8">
                                <div class="flex items-center justify-center border-b-4 border-black pb-4">
                                    <img src="{{ asset('storage/UHTP.png') }}" alt="Logo Universitas" class="h-24 mr-6">
                                    <div class="text-center">
                                        <p class="text-xl font-bold">UNIVERSITAS HANG TUAH PEKANBARU</p>
                                        <p class="text-sm">Jl. Mustafa Sari No. 05 Tangkerang Selatan, Telp. (0761) 77015, Fax. (0761) 861646</p>
                                        <p class="text-sm">Email: universitas@htp.ac.id atau Pendaftaran: 22INFO-2002 Website: http://www.htp.ac.id</p>
                                    </div>
                                </div>
                                <h3 class="text-center font-bold text-lg mt-6 mb-4 underline">TRANSKRIP NILAI AKADEMIK</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 mb-6 text-sm">
                                <div class="space-y-1">
                                    <div class="flex"><p class="w-32 text-gray-500 print:w-32 print:font-normal print:text-black">NIM</p><p>: {{ $mahasiswa->MhswID }}</p></div>
                                    <div class="flex"><p class="w-32 text-gray-500 print:w-32 print:font-normal print:text-black">NAMA</p><p>: {{ strtoupper($mahasiswa->Nama) }}</p></div>
                                    <div class="flex"><p class="w-32 text-gray-500 print:w-32 print:font-normal print:text-black">Tempat/Tgl Lahir</p><p>: {{ strtoupper($mahasiswa->TempatLahir) ?? '-' }}, {{ $mahasiswa->TanggalLahir ? \Carbon\Carbon::parse($mahasiswa->TanggalLahir)->format('d F Y') : '-' }}</p></div>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex"><p class="w-32 text-gray-500 print:w-32 print:font-normal print:text-black">Jenjang</p><p>: {{ $mahasiswa->prodi->JenjangID ?? '-' }}</p></div>
                                    <div class="flex"><p class="w-32 text-gray-500 print:w-32 print:font-normal print:text-black">Program Studi</p><p>: {{ $mahasiswa->prodi->Nama }}</p></div>
                                    <div class="flex"><p class="w-32 text-gray-500 print:w-32 print:font-normal print:text-black">Konsentrasi</p><p>: -</p></div>
                                </div>
                            </div>

                            <div class="overflow-x-auto border border-gray-200 rounded-lg print:border-none print:rounded-none">
                                <table class="min-w-full divide-y divide-gray-200 print:divide-none">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode MK</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Smt</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Mutu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 print:divide-none">
                                        @forelse($transkripDetail as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->MKKode }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->Nama }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $item->Semester }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $item->SKS }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $item->GradeNilai }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ number_format($item->BobotNilai, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ number_format($item->Mutu, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                                Data tidak ditemukan untuk rentang semester yang dipilih.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-600">Total</td>
                                            <td class="px-6 py-3 text-center text-sm font-medium text-gray-600">{{ $totalSksKumulatif }}</td>
                                            <td colspan="2"></td>
                                            <td class="px-6 py-3 text-center text-sm font-medium text-gray-600">{{ number_format($totalMutuKumulatif, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" class="px-6 py-3 text-right text-sm font-bold text-gray-700">Indeks Prestasi Kumulatif (IPK)</td>
                                            <td class="px-6 py-3 text-center text-sm font-bold text-gray-900">{{ number_format($ipk, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="hidden print:block mt-16">
                                <div class="flex justify-end">
                                    <div class="text-center">
                                        <p>Pekanbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                        <p>Wakil Rektor Bidang Akademik,</p>
                                        <div class="h-20"></div>
                                        <p class="font-bold underline">(Nama Wakil Rektor)</p>
                                        <p>NIDN. (Nomor NIDN)</p>
                                    </div>
                                </div>
                            </div>

                        @else
                            <div class="text-center py-8 text-gray-500 print:hidden">
                                <p>Silakan pilih rentang semester untuk menampilkan Transkrip Nilai.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
