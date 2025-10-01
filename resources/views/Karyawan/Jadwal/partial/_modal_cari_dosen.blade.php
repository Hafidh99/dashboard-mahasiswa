{{-- Modal Cari Dosen --}}
<div id="cariDosenModal" class="fixed inset-0 bg-gray-800 bg-opacity-60 overflow-y-auto h-full w-full hidden z-40">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Cari dan Pilih Dosen</h3>
        <input type="text" id="searchDosenInput" placeholder="Ketik untuk mencari NIDN atau Nama Dosen..." class="w-full p-2 border border-gray-300 rounded-md mb-4">
        <div id="dosenResults" class="max-h-60 overflow-y-auto"></div>
        <div class="text-right mt-4">
            <button id="tutupCariDosenModalBtn" type="button" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700">Tutup</button>
        </div>
    </div>
</div>