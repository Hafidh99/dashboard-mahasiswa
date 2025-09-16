<x-dosen-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Set Bobot Penilaian
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if($detailJadwal)
                        <div class="mb-6 border-b pb-4 text-sm text-gray-600">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                <div><strong>Matakuliah:</strong></div>
                                <div>{{ $detailJadwal->NamaMataKuliah }}</div>
                                <div><strong>Program Studi:</strong></div>
                                <div>{{ $detailJadwal->NamaProdi }}</div>
                                <div><strong>Kelas/Tahun:</strong></div>
                                <div>{{ $detailJadwal->NamaKelas }} / {{ $detailJadwal->TahunID }}</div>
                                <div><strong>Hari/Jam:</strong></div>
                                <div>{{ $hari[$detailJadwal->HariID] ?? 'N/A' }} / {{ substr($detailJadwal->JamMulai, 0, 5) }} - {{ substr($detailJadwal->JamSelesai, 0, 5) }}</div>
                            </div>
                        </div>
                    @endif


                    <h3 class="text-lg font-bold mb-6 text-center uppercase tracking-wider">Bobot Penilaian</h3>

                    <form action="{{ route('dosen.jadwal.bobot.update', $jadwal->JadwalID) }}" method="POST"
                        x-data="{
                            presensi: {{ old('Presensi', $jadwal->Presensi ?? 0) }},
                            tugas_mandiri: {{ old('TugasMandiri', $jadwal->TugasMandiri ?? 0) }},
                            tugas1: {{ old('Tugas1', $jadwal->Tugas1 ?? 0) }},
                            tugas2: {{ old('Tugas2', $jadwal->Tugas2 ?? 0) }},
                            tugas3: {{ old('Tugas3', $jadwal->Tugas3 ?? 0) }},
                            uts: {{ old('UTS', $jadwal->UTS ?? 0) }},
                            uas: {{ old('UAS', $jadwal->UAS ?? 0) }},
                              // Ganti nilai default 0 ini jika ada data Soft Skill & Lab di db
                            soft_skill: {{ old('soft_skill', $jadwal->SoftSkill ?? 0) }}, 
                            lab: {{ old('lab', $jadwal->Lab ?? 0) }},
                            total() {
                                  // Memastikan semua nilai adalah angka sebelum dijumlahkan
                                return (parseFloat(this.presensi) || 0) + 
                                        (parseFloat(this.tugas_mandiri) || 0) + 
                                        (parseFloat(this.tugas1) || 0) +
                                        (parseFloat(this.tugas2) || 0) + 
                                        (parseFloat(this.tugas3) || 0) + 
                                        (parseFloat(this.soft_skill) || 0) + 
                                        (parseFloat(this.lab) || 0) + 
                                        (parseFloat(this.uts) || 0) +
                                        (parseFloat(this.uas) || 0);
                            }
                        }">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                            <div class="space-y-3">
                                @php
                                    $bobotItems = [
                                        ['name' => 'Presensi', 'label' => 'Presensi', 'model' => 'presensi'],
                                        ['name' => 'TugasMandiri', 'label' => 'Tugas Mandiri', 'model' => 'tugas_mandiri'],
                                        ['name' => 'Tugas1', 'label' => 'Tugas 1', 'model' => 'tugas1'],
                                        ['name' => 'Tugas2', 'label' => 'Tugas 2', 'model' => 'tugas2'],
                                        ['name' => 'Tugas3', 'label' => 'Tugas 3', 'model' => 'tugas3'],
                                        ['name' => 'soft_skill', 'label' => 'Soft Skill', 'model' => 'soft_skill'],
                                        ['name' => 'lab', 'label' => 'Lab', 'model' => 'lab'],
                                        ['name' => 'UTS', 'label' => 'UTS', 'model' => 'uts'],
                                        ['name' => 'UAS', 'label' => 'UAS', 'model' => 'uas'],
                                    ];
                                @endphp

                                @foreach ($bobotItems as $item)
                                <div class="flex items-center">
                                    <label for="{{ $item['name'] }}" class="w-1/3">{{ $item['label'] }}</label>
                                    <div class="w-2/3 flex items-center">
                                        <input type="number" step="1" name="{{ $item['name'] }}" id="{{ $item['name'] }}" x-model.number="{{ $item['model'] }}" class="w-full form-input rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-gray-500">%</span>
                                    </div>
                                </div>
                                @if ($item['name'] === 'TugasMandiri')
                                <div class="pl-4 text-sm text-gray-600">
                                    <input type="checkbox" id="opsi_tugas" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="opsi_tugas">Ceklis Apabila Bobot Tugas Mandiri tidak akumulasi</label>
                                </div>
                                @endif
                                @endforeach
                                
                                <div class="flex items-center font-bold text-lg pt-4 border-t mt-4">
                                    <label for="total" class="w-1/3">TOTAL</label>
                                    <div class="w-2/3">
                                        <span x-text="total().toFixed(0)" :class="total() != 100 ? 'text-red-600 font-extrabold' : 'text-green-600 font-extrabold'"></span>
                                        <span class="ml-2" :class="total() != 100 ? 'text-red-600 font-extrabold' : 'text-green-600 font-extrabold'">%</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="catatan" class="block mb-2 font-medium text-gray-700">Catatan</label>
                                <textarea name="catatan" id="catatan" rows="10" class="w-full form-textarea rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('catatan', $jadwal->Catatan ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('dosen.jadwal.index') }}" class="inline-flex items-center px-6 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150" :disabled="total() != 100">
                                Simpan Bobot
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dosen-layout>
