{{-- Modal Tambah Jadwal --}}
<div id="tambahJadwalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-20">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-4">Tambah Jadwal</h3>
            <form action="{{ route('karyawan.jadwal.store') }}" method="POST">
                @csrf
                <input type="hidden" name="filter_tahun_id" value="{{ $input['tahun_id'] ?? '' }}">
                <input type="hidden" name="filter_program_id" value="{{ $input['program_id'] ?? '' }}">
                <input type="hidden" name="filter_prodi_id" value="{{ $input['prodi_id'] ?? '' }}">

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b pb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                            <input type="text" value="{{ $semuaProdi->where('ProdiID', $input['prodi_id'] ?? '')->first()->Nama ?? '' }}" class="mt-1 block w-full bg-gray-100 rounded-md border-gray-300 shadow-sm text-sm" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Program</label>
                            <input type="text" value="{{ $semuaProgram->where('ProgramID', $input['program_id'] ?? '')->first()->Nama ?? '' }}" class="mt-1 block w-full bg-gray-100 rounded-md border-gray-300 shadow-sm text-sm" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai Kuliah</label>
                            <input type="date" name="tanggal_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai Kuliah</label>
                            <input type="date" name="tanggal_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hari</label>
                            <select name="hari_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                @foreach($hari as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Kuliah</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <input type="time" name="jam_mulai" class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <span>-</span>
                                <input type="time" name="jam_selesai" class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                            </div>
                        </div>
                         <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Ruang</label>
                                <div class="flex items-center">
                                    <input type="text" id="ruang_id_input" name="ruang_id" class="mt-1 block w-full rounded-l-md border-gray-300 shadow-sm text-sm">
                                    <button type="button" id="cariRuangBtn" class="mt-1 px-3 py-2 bg-gray-200 text-gray-700 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-300">Cari...</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kapasitas</label>
                                <input type="number" name="kapasitas" id="kapasitas_jadwal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="border-b pb-4">
                         <label class="block text-sm font-medium text-gray-700 mb-1">Matakuliah</label>
                        <div class="flex items-center">
                            <input type="hidden" name="mk_id" id="mk_id_input">
                            <input type="text" name="mk_kode" id="mk_kode_input" class="block w-24 bg-gray-100 rounded-l-md border-gray-300 shadow-sm text-sm" readonly>
                            <input type="text" name="mk_nama" id="mk_nama_input" class="block w-full bg-gray-100 border-gray-300 shadow-sm text-sm" readonly>
                             <input type="text" name="mk_sks" id="mk_sks_input" class="block w-16 bg-gray-100 border-gray-300 shadow-sm text-sm text-center" readonly placeholder="SKS">
                            <button type="button" id="cariMkBtn" class="px-3 py-2 bg-gray-200 text-gray-700 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-300">Cari...</button>
                        </div>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="ada_responsi" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Ada Responsi/Lab?</span>
                            </label>
                        </div>
                    </div>

                    <div class="border-b pb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Pengampu</label>
                        <div class="flex items-center">
                             <input type="hidden" name="dosen_id" id="dosen_id_input">
                            <input type="text" name="dosen_display" id="dosen_display_input" class="block w-full bg-gray-100 rounded-l-md border-gray-300 shadow-sm text-sm" readonly>
                            <button type="button" id="cariDosenBtn" class="px-3 py-2 bg-gray-200 text-gray-700 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-300">Cari...</button>
                        </div>
                    </div>
                   
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b pb-4">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Kelas</label>
                             <select name="kelas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($semuaKelas as $kelas)
                                <option value="{{ $kelas->KelasID }}">{{ $kelas->Nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rencana Kehadiran Dosen</label>
                            <input type="number" name="rencana_kehadiran" value="16" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Maksimum Absen</label>
                            <input type="number" name="max_absen" value="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <div>
                             <label class="inline-flex items-center">
                                <input type="checkbox" id="adaBiayaKhusus" name="ada_biaya" class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                <span class="ml-2 text-sm text-gray-600">Ada Biaya Khusus?</span>
                            </label>
                        </div>
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Biaya</label>
                                <input type="number" name="biaya" id="inputBiaya" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm bg-gray-100" disabled>
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Biaya</label>
                                <input type="text" name="nama_biaya" id="inputNamaBiaya" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm bg-gray-100" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="items-center px-4 py-3 mt-4 text-right">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700">Simpan Jadwal</button>
                    <button id="tutupJadwalModalBtn" type="button" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>