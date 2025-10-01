document.addEventListener('DOMContentLoaded', function () {
    // Pastikan pageData ada, jika tidak, hentikan eksekusi script.
    if (typeof window.pageData === 'undefined') {
        console.error('Error: pageData object not found. Pastikan script di file Blade sudah benar.');
        return;
    }

    // Ambil semua data yang dibutuhkan dari objek global
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const routes = window.pageData.routes;
    const prodiId = window.pageData.currentProdiId;

    // ==================
    // Variabel Modal
    // ==================
    const tambahKelasModal = document.getElementById('tambahKelasModal');
    const tambahJadwalModal = document.getElementById('tambahJadwalModal');
    const editJadwalModal = document.getElementById('editJadwalModal');
    const cariRuangModal = document.getElementById('cariRuangModal');
    const cariMkModal = document.getElementById('cariMkModal');
    const cariDosenModal = document.getElementById('cariDosenModal');
    const editDosenModal = document.getElementById('editDosenModal');

    // ==================
    // Tombol Buka Modal (dicek dulu apakah elemennya ada)
    // ==================
    document.getElementById('tambahKelasBtn')?.addEventListener('click', () => tambahKelasModal.style.display = "block");
    document.getElementById('tambahJadwalBtn')?.addEventListener('click', () => tambahJadwalModal.style.display = "block");
    document.getElementById('cariRuangBtn')?.addEventListener('click', () => setupCari('ruang', 'tambah'));
    document.getElementById('cariMkBtn')?.addEventListener('click', () => setupCari('mk', 'tambah'));
    document.getElementById('cariDosenBtn')?.addEventListener('click', () => setupCari('dosen', 'tambah'));
    document.getElementById('edit_cariRuangBtn')?.addEventListener('click', () => setupCari('ruang', 'edit'));
    document.getElementById('edit_cariMkBtn')?.addEventListener('click', () => setupCari('mk', 'edit'));
    document.getElementById('edit_cariDosenBtn')?.addEventListener('click', () => setupCari('dosen', 'edit'));

    // ==================
    // Tombol Tutup Modal
    // ==================
    document.getElementById('tutupModalBtn')?.addEventListener('click', () => tambahKelasModal.style.display = "none");
    document.getElementById('tutupJadwalModalBtn')?.addEventListener('click', () => tambahJadwalModal.style.display = "none");
    document.getElementById('tutupEditJadwalModalBtn')?.addEventListener('click', () => editJadwalModal.style.display = "none");
    document.getElementById('tutupCariRuangModalBtn')?.addEventListener('click', () => cariRuangModal.style.display = "none");
    document.getElementById('tutupCariMkModalBtn')?.addEventListener('click', () => cariMkModal.style.display = "none");
    document.getElementById('tutupCariDosenModalBtn')?.addEventListener('click', () => cariDosenModal.style.display = "none");
    document.getElementById('tutupEditDosenModalBtn')?.addEventListener('click', () => editDosenModal.style.display = "none");
    document.getElementById('dosenModal_batalBtn')?.addEventListener('click', () => editDosenModal.style.display = "none");

    // ================================
    // LOGIKA MODAL EDIT JADWAL
    // ================================
    document.querySelectorAll('.edit-jadwal-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const jsonUrl = this.dataset.jsonUrl;
            const updateUrl = this.dataset.updateUrl;
            const editForm = document.getElementById('editJadwalForm');
            
            fetch(jsonUrl)
                .then(response => {
                    if (!response.ok) { throw new Error('Jadwal tidak ditemukan'); }
                    return response.json();
                })
                .then(data => {
                    editForm.action = updateUrl;
                    document.getElementById('edit_program_id').value = data.ProgramID;
                    document.getElementById('edit_tanggal_mulai').value = data.TglMulai;
                    document.getElementById('edit_tanggal_selesai').value = data.TglSelesai;
                    document.getElementById('edit_hari_id').value = data.HariID;
                    document.getElementById('edit_jam_mulai').value = data.JamMulai ? data.JamMulai.substring(0, 5) : '';
                    document.getElementById('edit_jam_selesai').value = data.JamSelesai ? data.JamSelesai.substring(0, 5) : '';
                    document.getElementById('edit_ruang_id_input').value = data.RuangID;
                    document.getElementById('edit_kapasitas_jadwal').value = data.Kapasitas;
                    document.getElementById('edit_mk_id_input').value = data.MKID;
                    document.getElementById('edit_mk_kode_input').value = data.MKKode;
                    document.getElementById('edit_mk_nama_input').value = data.NamaMK;
                    document.getElementById('edit_mk_sks_input').value = data.SKS;
                    document.getElementById('edit_ada_responsi').checked = (data.AdaResponsi == 'Y');
                    document.getElementById('edit_dosen_id_input').value = data.DosenID;
                    document.getElementById('edit_dosen_display_input').value = `${data.NamaDosen || 'N/A'}, ${data.Gelar || ''}`;
                    document.getElementById('edit_kelas_id').value = data.KelasID;
                    document.getElementById('edit_rencana_kehadiran').value = data.RencanaKehadiran;
                    document.getElementById('edit_max_absen').value = data.MaxAbsen;

                    const checkboxBiaya = document.getElementById('edit_adaBiayaKhusus');
                    const inputBiaya = document.getElementById('edit_inputBiaya');
                    const inputNamaBiaya = document.getElementById('edit_inputNamaBiaya');
                    const hasBiaya = data.Biaya && data.Biaya > 0;
                    checkboxBiaya.checked = hasBiaya;
                    inputBiaya.disabled = !hasBiaya;
                    inputNamaBiaya.disabled = !hasBiaya;
                    inputBiaya.value = hasBiaya ? data.Biaya : '';
                    inputNamaBiaya.value = hasBiaya ? data.NamaBiaya : '';
                    if (hasBiaya) {
                        inputBiaya.classList.remove('bg-gray-100');
                        inputNamaBiaya.classList.remove('bg-gray-100');
                    } else {
                        inputBiaya.classList.add('bg-gray-100');
                        inputNamaBiaya.classList.add('bg-gray-100');
                    }
                    editJadwalModal.style.display = 'block';
                })
                .catch(error => {
                    alert(error.message);
                });
        });
    });

    // ================================
    // LOGIKA LIVE SEARCH
    // ================================
    let currentSearchContext = { type: '', mode: '' };

    function setupCari(type, mode) {
        currentSearchContext = { type, mode };
        if (type === 'ruang') cariRuangModal.style.display = "block";
        if (type === 'mk') cariMkModal.style.display = "block";
        if (type === 'dosen') cariDosenModal.style.display = "block";
    }

    document.getElementById('searchRuangInput')?.addEventListener('keyup', function() {
        liveSearch(this.value, routes.searchRuang, document.getElementById('ruangResults'), (row, item) => {
            row.innerHTML = `<td class="px-4 py-2">${item.RuangID}</td><td class="px-4 py-2">${item.Nama}</td><td class="px-4 py-2">${item.Kapasitas}</td>`;
            row.onclick = () => {
                const prefix = currentSearchContext.mode === 'edit' ? 'edit_' : '';
                document.getElementById(`${prefix}ruang_id_input`).value = item.RuangID;
                document.getElementById(`${prefix}kapasitas_jadwal`).value = item.Kapasitas;
                cariRuangModal.style.display = 'none';
            };
        }, ['ID Ruang', 'Nama', 'Kapasitas']);
    });

    document.getElementById('searchMkInput')?.addEventListener('keyup', function() {
        // Search MK butuh prodiId, jadi kita tambahkan ke URL
        const url = `${routes.searchMk}?prodi_id=${prodiId}`;
        liveSearch(this.value, url, document.getElementById('mkResults'), (row, item) => {
            row.innerHTML = `<td class="px-4 py-2">${item.MKKode}</td><td class="px-4 py-2">${item.Nama}</td><td class="px-4 py-2">${item.SKS}</td><td class="px-4 py-2">${item.Sesi}</td>`;
            row.onclick = () => {
                const prefix = currentSearchContext.mode === 'edit' ? 'edit_' : '';
                document.getElementById(`${prefix}mk_id_input`).value = item.MKID;
                document.getElementById(`${prefix}mk_kode_input`).value = item.MKKode;
                document.getElementById(`${prefix}mk_nama_input`).value = item.Nama;
                document.getElementById(`${prefix}mk_sks_input`).value = item.SKS;
                cariMkModal.style.display = 'none';
            };
        }, ['Kode', 'Nama', 'SKS', 'SMT']);
    });

    document.getElementById('searchDosenInput')?.addEventListener('keyup', function() {
        // Search Dosen juga butuh prodiId
        const url = `${routes.searchDosen}?prodi_id=${prodiId}`;
        liveSearch(this.value, url, document.getElementById('dosenResults'), (row, item) => {
            row.innerHTML = `<td class="px-4 py-2">${item.Login}</td><td class="px-4 py-2">${item.Nama}, ${item.Gelar}</td>`;
            row.onclick = () => {
                if (currentSearchContext.mode === 'tambah_tim') {
                    addDosenToTeam(item);
                } else {
                    const prefix = currentSearchContext.mode === 'edit' ? 'edit_' : '';
                    document.getElementById(`${prefix}dosen_id_input`).value = item.Login;
                    document.getElementById(`${prefix}dosen_display_input`).value = `${item.Nama}, ${item.Gelar}`;
                }
                cariDosenModal.style.display = 'none';
            };
        }, ['NIDN', 'Nama Dosen']);
    });

    function liveSearch(query, url, resultsContainer, rowBuilder, headers = []) {
        if (query.length < 2) { // Minimal 2 karakter untuk mulai mencari
            resultsContainer.innerHTML = '';
            return;
        }

        // Menambahkan parameter 'query' ke URL dengan benar
        const finalUrl = url.includes('?') ? `${url}&query=${query}` : `${url}?query=${query}`;

        fetch(finalUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                resultsContainer.innerHTML = '';
                if (data.length > 0) {
                    const table = document.createElement('table');
                    table.className = 'min-w-full divide-y divide-gray-200 text-sm';
                    if (headers.length > 0) {
                        const theadContent = headers.map(h => `<th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">${h}</th>`).join('');
                        table.innerHTML = `<thead class="bg-gray-50"><tr>${theadContent}</tr></thead>`;
                    }
                    const tbody = document.createElement('tbody');
                    tbody.className = 'bg-white divide-y divide-gray-200';
                    data.forEach(item => {
                        const row = tbody.insertRow();
                        row.className = 'cursor-pointer hover:bg-gray-100';
                        rowBuilder(row, item);
                    });
                    table.appendChild(tbody);
                    resultsContainer.appendChild(table);
                } else {
                    resultsContainer.innerHTML = '<p class="text-gray-500 p-4">Tidak ada data ditemukan.</p>';
                }
            })
            .catch(error => {
                console.error('Live Search Error:', error);
                resultsContainer.innerHTML = '<p class="text-red-500 p-4">Terjadi kesalahan saat mencari.</p>';
            });
    }
    
    function setupBiayaKhusus(checkboxId, inputBiayaId, inputNamaBiayaId) {
        const checkbox = document.getElementById(checkboxId);
        const inputBiaya = document.getElementById(inputBiayaId);
        const inputNamaBiaya = document.getElementById(inputNamaBiayaId);
        if (checkbox) {
            checkbox.onchange = function() {
                const isChecked = this.checked;
                inputBiaya.disabled = !isChecked;
                inputNamaBiaya.disabled = !isChecked;
                if (isChecked) {
                    inputBiaya.classList.remove('bg-gray-100');
                    inputNamaBiaya.classList.remove('bg-gray-100');
                } else {
                    inputBiaya.value = '';
                    inputNamaBiaya.value = '';
                    inputBiaya.classList.add('bg-gray-100');
                    inputNamaBiaya.classList.add('bg-gray-100');
                }
            };
        }
    }
    setupBiayaKhusus('adaBiayaKhusus', 'inputBiaya', 'inputNamaBiaya');
    setupBiayaKhusus('edit_adaBiayaKhusus', 'edit_inputBiaya', 'edit_inputNamaBiaya');

    // ===================================
    // LOGIKA UNTUK MODAL TIM DOSEN
    // ===================================
    const dosenListContainer = document.getElementById('dosenModal_list');
    let currentJadwalId = null;

    const renderDosenList = (dosenList) => {
        dosenListContainer.innerHTML = '';
        if (!dosenList || dosenList.length === 0) {
            dosenListContainer.innerHTML = '<p class="text-gray-400 italic text-sm">Belum ada dosen di tim ini.</p>';
            return;
        }
        dosenList.forEach(dosen => {
            const isUtama = dosen.JenisDosenID === 'DSN';
            const dosenDiv = document.createElement('div');
            dosenDiv.className = 'flex items-center justify-between bg-gray-50 p-2 rounded';
            dosenDiv.dataset.dosenId = dosen.DosenID;

            dosenDiv.innerHTML = `
                <div>
                    <span class="font-medium text-gray-800">${dosen.Nama}, ${dosen.Gelar}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <label class="text-sm flex items-center cursor-pointer">
                        <input type="radio" name="dosen_utama" value="${dosen.DosenID}" ${isUtama ? 'checked' : ''} class="mr-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        Utama
                    </label>
                    <button type="button" class="remove-dosen-btn text-red-500 hover:text-red-700 font-bold text-lg leading-none" title="Hapus dari tim">&times;</button>
                </div>
            `;
            dosenListContainer.appendChild(dosenDiv);
        });
    };
    
    document.querySelectorAll('.edit-dosen-btn').forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            currentJadwalId = this.dataset.jadwalId;
            const url = routes.getDosenTeam.replace('{jadwalId}', currentJadwalId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    document.getElementById('dosenModal_namaMK').textContent = data.jadwal.NamaMK;
                    document.getElementById('dosenModal_kodeMK').textContent = `(${data.jadwal.MKKode})`;
                    document.getElementById('dosenModal_hari').textContent = data.jadwal.Hari;
                    document.getElementById('dosenModal_jam').textContent = `${data.jadwal.JamMulai.substr(0,5)} - ${data.jadwal.JamSelesai.substr(0,5)}`;
                    renderDosenList(data.timDosen);
                    editDosenModal.style.display = 'block';
                });
        };
    });

    document.getElementById('dosenModal_cariDosenBtn')?.addEventListener('click', () => {
        setupCari('dosen', 'tambah_tim');
    });
    
    function addDosenToTeam(dosenItem) {
        const existingIds = Array.from(dosenListContainer.querySelectorAll('[data-dosen-id]')).map(div => div.dataset.dosenId);
        if (existingIds.includes(dosenItem.Login)) {
            alert('Dosen sudah ada di dalam tim.');
            return;
        }
        
        const isFirstDosen = dosenListContainer.querySelector('p') !== null;
        const newDosen = {
            DosenID: dosenItem.Login,
            Nama: dosenItem.Nama,
            Gelar: dosenItem.Gelar,
            JenisDosenID: isFirstDosen ? 'DSN' : 'DSC'
        };
        
        const currentList = existingIds.length > 0 ? Array.from(dosenListContainer.querySelectorAll('[data-dosen-id]')).map(div => ({
            DosenID: div.dataset.dosenId,
            Nama: div.querySelector('.font-medium').textContent.split(',')[0],
            Gelar: div.querySelector('.font-medium').textContent.split(',')[1]?.trim() || '',
            JenisDosenID: div.querySelector('input[type="radio"]').checked ? 'DSN' : 'DSC'
        })) : [];

        currentList.push(newDosen);
        renderDosenList(currentList);
    }
    
    dosenListContainer?.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-dosen-btn')) {
            e.target.closest('[data-dosen-id]').remove();
            if (dosenListContainer.children.length === 0) {
                 dosenListContainer.innerHTML = '<p class="text-gray-400 italic text-sm">Belum ada dosen di tim ini.</p>';
            } else {
                const firstRadio = dosenListContainer.querySelector('input[type="radio"]');
                if (firstRadio && !dosenListContainer.querySelector('input[type="radio"]:checked')) {
                    firstRadio.checked = true;
                }
            }
        }
    });

    document.getElementById('dosenModal_simpanBtn')?.addEventListener('click', function() {
        const dosenDivs = dosenListContainer.querySelectorAll('[data-dosen-id]');
        const dosen_ids = Array.from(dosenDivs).map(div => div.dataset.dosenId);
        const dosen_utama_id = dosenListContainer.querySelector('input[name="dosen_utama"]:checked')?.value;

        if (dosen_ids.length > 0 && !dosen_utama_id) {
            alert('Silakan pilih salah satu dosen sebagai dosen utama.');
            return;
        }

        const url = routes.updateDosenTeam.replace('{jadwalId}', currentJadwalId);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                dosen_ids: dosen_ids,
                dosen_utama_id: dosen_utama_id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                window.location.reload(); 
            } else {
                alert(data.error || 'Terjadi kesalahan.');
            }
        });
    });

    // Tutup modals saat klik di luar area modal
    window.onclick = function(event) {
        if (event.target == tambahKelasModal) tambahKelasModal.style.display = "none";
        if (event.target == tambahJadwalModal) tambahJadwalModal.style.display = "none";
        if (event.target == editJadwalModal) editJadwalModal.style.display = "none";
        if (event.target == cariRuangModal) cariRuangModal.style.display = "none";
        if (event.target == cariMkModal) cariMkModal.style.display = "none";
        if (event.target == cariDosenModal) cariDosenModal.style.display = "none";
        if (event.target == editDosenModal) editDosenModal.style.display = "none";
    }
});

