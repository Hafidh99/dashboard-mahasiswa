{{-- Modal Tambah Kelas --}}
<div id="tambahKelasModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-20">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Kelas Baru</h3>
            <div class="mt-2 px-7 py-3">
                <form action="{{ route('karyawan.kelas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="filter_tahun_id" value="{{ $input['tahun_id'] ?? '' }}">
                    <input type="hidden" name="filter_program_id" value="{{ $input['program_id'] ?? '' }}">
                    <input type="hidden" name="filter_prodi_id" value="{{ $input['prodi_id'] ?? '' }}">
                    <div class="mb-4 text-left">
                        <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas:</label>
                        <input type="text" name="nama_kelas" id="nama_kelas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                    </div>
                    <div class="mb-4 text-left">
                        <label for="tahun_akademik" class="block text-sm font-medium text-gray-700">Tahun Akademik:</label>
                        <input type="text" name="tahun_akademik" id="tahun_akademik" value="{{ $input['tahun_id'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                    </div>
                    <div class="mb-4 text-left">
                        <label for="modal_prodi_id" class="block text-sm font-medium text-gray-700">Program Studi:</label>
                        <select name="prodi_id" id="modal_prodi_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                            @foreach($semuaProdi as $prodi)
                                <option value="{{ $prodi->ProdiID }}" {{ ($input['prodi_id'] ?? '') == $prodi->ProdiID ? 'selected' : '' }}>{{ $prodi->Nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4 text-left">
                        <label for="modal_program_id" class="block text-sm font-medium text-gray-700">Program:</label>
                        <select name="program_id" id="modal_program_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                            @foreach($semuaProgram as $program)
                                <option value="{{ $program->ProgramID }}" {{ ($input['program_id'] ?? '') == $program->ProgramID ? 'selected' : '' }}>{{ $program->Nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4 text-left">
                        <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas Maksimum:</label>
                        <input type="number" name="kapasitas" id="kapasitas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-auto shadow-sm hover:bg-green-700">Simpan Kelas</button>
                        <button type="reset" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-auto shadow-sm hover:bg-gray-700">Reset</button>
                        <button id="tutupModalBtn" type="button" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-auto shadow-sm hover:bg-red-700">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
