<x-dosen-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Presensi & Jurnal Kuliah: {{ $detailJadwal->NamaMataKuliah }}
</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900" x-data="{ 
                showAddForm: false, 
                showEditModal: false, 
                editingPertemuan: {},
                deleteUrl: '' 
            }">
                
                <!-- Detail Mata Kuliah -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p><strong>Nama:</strong> {{ $detailJadwal->NamaMataKuliah }}</p>
                        <p><strong>Program Studi:</strong> {{ $detailJadwal->NamaProdi }}</p>
                        <p><strong>Kelas / Tahun Akademik:</strong> {{ $detailJadwal->NamaKelas }} / {{ $detailJadwal->TahunID }}</p>
                    </div>
                    <div>
                        <p><strong>Hari / Jam:</strong> {{ $hari[$detailJadwal->HariID] ?? '' }} / {{ substr($detailJadwal->JamMulai, 0, 5) }} - {{ substr($detailJadwal->JamSelesai, 0, 5) }}</p>
                        <p><strong>Dosen:</strong> {{ $detailJadwal->NamaDosen }}, {{ $detailJadwal->Gelar }}</p>
                    </div>
                </div>
                
                <div class="border-b pb-6 mb-6">
                    <button @click="showAddForm = !showAddForm" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <span>Tambah Pertemuan</span>
                    </button>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-300 rounded-md p-3">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form Tambah Pertemuan -->
                <div x-show="showAddForm" x-transition class="mb-8 p-4 border rounded-md bg-gray-50" style="display: none;">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Tambah Pertemuan</h3>
                    <form action="{{ route('dosen.jadwal.presensi.store', $jadwal->JadwalID) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="pertemuan" class="block text-sm font-medium text-gray-700">Pertemuan Ke</label>
                                <input type="number" name="pertemuan" id="pertemuan" value="{{ old('pertemuan', ($daftarPertemuan->max('Pertemuan') ?? 0) + 1) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan Dosen (Materi)</label>
                                <textarea name="catatan" id="catatan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('catatan') }}</textarea>
                            </div>
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="mt-6 text-right">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabel Daftar Pertemuan -->
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Presensi ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pertemuan Ke</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Kuliah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Absensi Mhs</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($daftarPertemuan as $pertemuan)
                                <tr>
                                    <td class="px-4 py-4 text-center text-sm">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $pertemuan->PresensiID }}</td>
                                    <td class="px-4 py-4 text-sm font-medium">{{ $pertemuan->Pertemuan }}</td>
                                    <td class="px-4 py-4 text-sm">{{ \Carbon\Carbon::parse($pertemuan->Tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="px-4 py-4 text-sm">{{ substr($pertemuan->JamMulai, 0, 5) }} - {{ substr($pertemuan->JamSelesai, 0, 5) }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $pertemuan->NamaDosenPengajar }}</td>
                                    <td class="px-4 py-4 text-sm">{{ $pertemuan->Catatan }}</td>
                                    <td class="px-4 py-4 text-center text-sm">
                                        <a href="{{ route('dosen.presensi.absen.edit', $pertemuan->PresensiID) }}" class="inline-block px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">Edit Absen</a>
                                    </td>
                                    <td class="px-4 py-4 text-center text-sm space-x-1">
                                        <button @click="editingPertemuan = {{ json_encode($pertemuan) }}; showEditModal = true" class="inline-block px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">Edit Pertemuan</button>
                                        
                                        <form action="{{ route('dosen.presensi.destroy', $pertemuan->PresensiID) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertemuan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">Hapus Absen</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-8 text-gray-500">
                                        Belum ada data pertemuan untuk mata kuliah ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal Edit Pertemuan -->
                <div x-show="showEditModal" style="display: none;" @keydown.escape.window="showEditModal = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <!-- Background overlay -->
                        <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showEditModal = false" aria-hidden="true"></div>

                        <!-- Modal panel -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                            <div class="flex items-center justify-between pb-3 border-b">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Ubah Pertemuan Dosen</h3>
                                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div class="mt-4">
                                <form :action="`/dosen/presensi/${editingPertemuan.PresensiID}`" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="edit_pertemuan" class="block text-sm font-medium text-gray-700">Pertemuan Ke</label>
                                            <input type="number" name="pertemuan" id="edit_pertemuan" x-model="editingPertemuan.Pertemuan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="edit_catatan" class="block text-sm font-medium text-gray-700">Catatan Dosen (Materi)</label>
                                            <textarea name="catatan" id="edit_catatan" x-model="editingPertemuan.Catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                        </div>
                                        <div>
                                            <label for="edit_tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                            <input type="date" name="tanggal" id="edit_tanggal" x-model="editingPertemuan.Tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="edit_jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                            <input type="time" name="jam_mulai" id="edit_jam_mulai" x-model="editingPertemuan.JamMulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="edit_jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                            <input type="time" name="jam_selesai" id="edit_jam_selesai" x-model="editingPertemuan.JamSelesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                    <div class="mt-6 flex justify-end space-x-4">
                                        <button type="button" @click="showEditModal = false" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Batal
                                        </button>
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

</x-dosen-layout>