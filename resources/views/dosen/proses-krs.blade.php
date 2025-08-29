<x-dosen-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Proses KRS Mahasiswa
        </h2>
    </x-slot>

    {{-- Script untuk Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ showModal: false }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">

                    <!-- INFORMASI KRS MAHASISWA -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Kartu Rencana Studi (KRS)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 mb-6 text-sm">
                            <div class="space-y-1">
                                <div class="flex"><p class="w-32 text-gray-500">Nama Mahasiswa</p><p>: {{ $mahasiswa->Nama }}</p></div>
                                <div class="flex"><p class="w-32 text-gray-500">NIM</p><p>: {{ $mahasiswa->MhswID }}</p></div>
                                <div class="flex"><p class="w-32 text-gray-500">Penasehat Akademik</p><p>: {{ $mahasiswa->pembimbingAkademik->Nama ?? '-' }}</p></div>
                            </div>
                            <div class="space-y-1">
                                <div class="flex"><p class="w-32 text-gray-500">Program Studi</p><p>: {{ $mahasiswa->prodi->Nama ?? '-' }}</p></div>
                                <div class="flex"><p class="w-32 text-gray-500">Tahun Akademik</p><p>: {{ $khs->TahunID }}</p></div>
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode MK</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matakuliah</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hari & Jam</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $hari = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']; @endphp
                                    @foreach($krsDetail as $krs)
                                    <tr>
                                        <td class="px-6 py-4 text-center text-sm">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $krs->MKKode }}</td>
                                        <td class="px-6 py-4 text-sm font-medium">{{ $krs->Nama }}</td>
                                        <td class="px-6 py-4 text-center text-sm">{{ $krs->SKS }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $krs->NamaDosen ? $krs->NamaDosen . ', ' . $krs->Gelar : '-' }}</td>
                                        <td class="px-6 py-4 text-center text-sm">{{ $hari[$krs->HariID] ?? '' }} <br> {{ \Carbon\Carbon::parse($krs->JamMulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($krs->JamSelesai)->format('H:i') }}</td>
                                        <td class="px-6 py-4 text-center text-sm">
                                            @if($krs->aprv_pa == 'Y')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                                <span class="block text-gray-500 text-xs">{{ \Carbon\Carbon::parse($krs->tgl_aprovePA)->format('d-m-Y') }}</span>
                                            @elseif($krs->aprv_pa == 'N')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-600">Total SKS</td>
                                        <td class="px-6 py-3 text-center text-sm font-bold text-gray-800">{{ $totalSks }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-start items-center space-x-3">
                            
                            <a href="{{ route('dosen.pa.list') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                &larr; Kembali
                            </a>

                            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cetak KHS
                            </button>

                            <button @click="showModal = true" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Proses
                            </button>

                        </div>

                    <!-- RINCIAN KEUANGAN MAHASISWA -->
                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Rincian Tagihan Keuangan Mahasiswa</h3>
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester/TA</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tagihan</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Potongan</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Terbayar</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $totalSisa = 0; @endphp
                                    @forelse($riwayatKeuangan as $item)
                                    @php $sisa = $item->Biaya - $item->Potongan - $item->Bayar; $totalSisa += $sisa; @endphp
                                    <tr>
                                        <td class="px-6 py-4 text-sm">{{ $item->Sesi }} / {{ $item->TahunID }}</td>
                                        <td class="px-6 py-4 text-sm text-right">Rp. {{ number_format($item->Biaya) }}</td>
                                        <td class="px-6 py-4 text-sm text-right">Rp. {{ number_format($item->Potongan) }}</td>
                                        <td class="px-6 py-4 text-sm text-right">Rp. {{ number_format($item->Bayar) }}</td>
                                        <td class="px-6 py-4 text-sm text-right font-medium {{ $sisa > 0 ? 'text-red-600' : 'text-green-600' }}">Rp. {{ number_format($sisa) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center py-4">Tidak ada riwayat keuangan.</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-gray-100">
                                    <tr>
                                        <td colspan="4" class="px-6 py-3 text-right font-bold">Sisa Hutang :</td>
                                        <td class="px-6 py-3 text-right font-bold text-red-600">Rp. {{ number_format($totalSisa) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- PERKEMBANGAN NILAI -->
                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Perkembangan IPS dan IPK Mahasiswa</h3>
                        <div class="p-4 border rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Grafik -->
                                <div class="h-64">
                                    <canvas id="nilaiChart"
                                        data-labels='@json($labels)'
                                        data-ips='@json($dataIPS)'
                                        data-ipk='@json($dataIPK)'>
                                    </canvas>
                                </div>
                                <!-- Tabel Detail Nilai -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Semester</th>
                                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">IPS</th>
                                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">IPK</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            {{-- PERBAIKAN: Looping menggunakan $riwayatStudi --}}
                                            @forelse($riwayatStudi as $index => $item)
                                            <tr>
                                                <td class="px-4 py-2 text-sm">Semester {{ $item->Sesi }} ({{ $item->TahunID }})</td>
                                                <td class="px-4 py-2 text-sm text-center">{{ number_format(($item->total_sks_semester > 0 ? $item->total_bobot_semester / $item->total_sks_semester : 0), 2) }}</td>
                                                <td class="px-4 py-2 text-sm text-center">{{ number_format($dataIPK[$index] ?? 0, 2) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-4 text-sm text-gray-500">Belum ada data nilai.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot class="bg-gray-100 font-bold">
                                            <tr>
                                                <td colspan="2" class="px-4 py-2 text-right">IPK Total</td>
                                                <td class="px-4 py-2 text-center">{{ number_format($ipk, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- MODAL PROSES KRS -->
                <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                    <div @click.away="showModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                        <div class="flex justify-between items-center border-b pb-3">
                            <h3 class="text-lg font-medium">Proses Persetujuan KRS</h3>
                            <button @click="showModal = false" class="text-gray-500 hover:text-gray-800">&times;</button>
                        </div>
                        <form method="POST" action="{{ route('dosen.pa.process.store', $khs->KHSID) }}" class="mt-4">
                            @csrf
                            <div class="space-y-4">
                                <fieldset>
                                    <legend class="sr-only">Status Persetujuan</legend>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <input id="diterima" name="status" type="radio" value="Y" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                            <label for="diterima" class="ml-3 block text-sm font-medium text-gray-700">Diterima</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="ditolak" name="status" type="radio" value="N" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                            <label for="ditolak" class="ml-3 block text-sm font-medium text-gray-700">Ditolak</label>
                                        </div>
                                    </div>
                                </fieldset>
                                <div>
                                    <label for="komentar" class="block text-sm font-medium text-gray-700">Catatan/Komentar PA :</label>
                                    <textarea id="komentar" name="komentar" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" @click="showModal = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Close</button>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Proses</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('nilaiChart');
            if (ctx) {
                const labels = JSON.parse(ctx.dataset.labels);
                const ipsData = JSON.parse(ctx.dataset.ips);
                const ipkData = JSON.parse(ctx.dataset.ipk);
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'IPS',
                            data: ipsData,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            tension: 0.1
                        }, {
                            label: 'IPK',
                            data: ipkData,
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.5)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 4.0
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-dosen-layout>
