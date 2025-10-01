{{-- =================================================== --}}
{{-- BARU: Modal untuk Edit Tim Dosen --}}
{{-- =================================================== --}}
<div id="editDosenModal" class="fixed inset-0 bg-gray-800 bg-opacity-60 overflow-y-auto h-full w-full hidden z-30">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-xl font-medium text-gray-900">Edit Tim Dosen Pengajar</h3>
            <button id="tutupEditDosenModalBtn" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>

        <div class="mt-4">
            <!-- Info Jadwal (diisi oleh JS) -->
            <div class="p-4 mb-4 bg-blue-50 border border-blue-200 rounded-lg text-sm grid grid-cols-2 md:grid-cols-4 gap-2">
                <div><strong>Matakuliah:</strong> <span id="dosenModal_namaMK"></span></div>
                <div><strong>Kode:</strong> <span id="dosenModal_kodeMK"></span></div>
                <div><strong>Hari:</strong> <span id="dosenModal_hari"></span></div>
                <div><strong>Jam:</strong> <span id="dosenModal_jam"></span></div>
            </div>

            <!-- Daftar Dosen Saat Ini -->
            <h4 class="font-semibold mb-2">Tim Dosen Saat Ini:</h4>
            <div id="dosenModal_list" class="space-y-2 mb-6 border rounded-md p-3 min-h-[50px]">
                {{-- Daftar dosen akan diisi oleh JavaScript di sini --}}
            </div>

            <!-- Form Tambah Dosen -->
            <div class="border-t pt-4">
                <h4 class="font-semibold mb-2">Tambah Dosen Baru:</h4>
                 <div class="flex items-center">
                    <input type="text" id="dosenModal_add_display_input" class="block w-full bg-gray-100 rounded-l-md border-gray-300 shadow-sm text-sm" readonly placeholder="Klik 'Cari...' untuk memilih dosen">
                    <button type="button" id="dosenModal_cariDosenBtn" class="px-3 py-2 bg-gray-200 text-gray-700 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-300">Cari...</button>
                </div>
            </div>

        </div>
        <div class="items-center px-4 py-3 mt-4 text-right border-t">
            <button id="dosenModal_simpanBtn" type="button" class="px-5 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700">Simpan Perubahan</button>
            <button id="dosenModal_batalBtn" type="button" class="px-5 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700">Batal</button>
        </div>
    </div>
</div>