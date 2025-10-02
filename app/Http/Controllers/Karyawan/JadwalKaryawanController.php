<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception; 

class JadwalKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $semuaTahun = DB::table('tahun')->select('TahunID')->distinct()->orderBy('TahunID', 'desc')->get();
        $semuaProdi = DB::table('prodi')->where('NA', 'N')->orderBy('Nama', 'asc')->get();
        $semuaProgram = DB::table('program')->where('NA', 'N')->orderBy('Nama', 'asc')->get();
        $hari = [ 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu' ];

        $jadwalDikelompokkan = collect();
        $semuaKelas = collect();
        $teamDosen = collect(); 

        if ($request->filled('tahun_id') || $request->filled('prodi_id') || $request->filled('program_id') || $request->filled('hari_id') || $request->filled('kelas_id') || $request->filled('filter_mk') || $request->filled('semester_mk')) {
            $query = DB::table('jadwal as j')
                ->leftJoin('dosen as d', 'j.DosenID', '=', 'd.Login') 
                ->join('kelas as k', 'j.NamaKelas', '=', 'k.KelasID') 
                ->leftJoin('mk', 'j.MKID', '=', 'mk.MKID')
                ->where('j.NA', 'N');

            if ($request->filled('tahun_id')) {
                $query->where('j.TahunID', $request->tahun_id);
            }
            if ($request->filled('prodi_id')) {
                $query->where('j.ProdiID', $request->prodi_id);
            }
            if ($request->filled('program_id')) {
                $query->where('j.ProgramID', $request->program_id);
            }
            if ($request->filled('hari_id')) {
                $query->where('j.HariID', $request->hari_id);
            }
            if ($request->filled('kelas_id')) {
                $query->where('j.NamaKelas', $request->kelas_id);
            }
            if ($request->filled('filter_mk')) {
                $query->where(function ($q) use ($request) {
                    $q->where('j.Nama', 'like', '%' . $request->filter_mk . '%')
                    ->orWhere('j.MKKode', 'like', '%' . $request->filter_mk . '%');
                });
            }
            if ($request->filled('semester_mk')) {
                $query->where('mk.Sesi', $request->semester_mk);
            }

            $jadwal = $query->select(
                'j.*', 
                'd.Nama as NamaDosen', 
                'd.Gelar', 
                'mk.Sesi as Semester',
                'k.Nama as NamaKelasDariRelasi'
            )
            ->orderBy('j.HariID', 'asc')
            ->orderBy('j.JadwalID', 'asc') 
            ->get();
            
            if ($jadwal->isNotEmpty()) {
                $jadwalIds = $jadwal->pluck('JadwalID')->toArray();

                // Ambil tim dosen dari tabel jadwaldosen
                $teamDosen = DB::table('jadwaldosen as jd')
                    ->join('dosen as d', 'jd.DosenID', '=', 'd.Login')
                    ->whereIn('jd.JadwalID', $jadwalIds)
                    ->select('jd.JadwalID', 'jd.DosenID', 'd.Nama', 'd.Gelar', 'jd.JenisDosenID')
                    ->get()
                    ->groupBy('JadwalID');

                // Menggabungkan Dosen Utama dari tabel 'jadwal' dengan Tim Dosen dari 'jadwaldosen' untuk ditampilkan.
                foreach ($jadwal as $j) {
                    $teamForJadwal = $teamDosen->get($j->JadwalID, collect());
                    $teamDosenIds = $teamForJadwal->pluck('DosenID');

                    if ($j->DosenID && !$teamDosenIds->contains($j->DosenID)) {
                        $mainLecturer = (object) [
                            'JadwalID' => $j->JadwalID,
                            'DosenID' => $j->DosenID,
                            'Nama' => $j->NamaDosen,
                            'Gelar' => $j->Gelar,
                            'JenisDosenID' => 'DSN' 
                        ];
                        $teamForJadwal->prepend($mainLecturer);
                    }

                    $sortedTeam = $teamForJadwal->sortByDesc(function ($dosen) {
                        return $dosen->JenisDosenID === 'DSN';
                    });

                    $teamDosen->put($j->JadwalID, $sortedTeam);
                }
            }

            $jadwalDikelompokkan = $jadwal->groupBy('HariID');

            if($request->filled('tahun_id') && $request->filled('prodi_id') && $request->filled('program_id')) {
                $semuaKelas = DB::table('kelas')
                    ->where('TahunID', $request->tahun_id)
                    ->where('ProdiID', 'like', '%' . $request->prodi_id . '%')
                    ->where('ProgramID', $request->program_id)
                    ->where('NA', 'N')
                    ->orderBy('Nama')
                    ->get();
            }
        }

        return view('Karyawan.Jadwal.index', [ 
            'semuaTahun' => $semuaTahun,
            'semuaProdi' => $semuaProdi,
            'semuaProgram' => $semuaProgram,
            'hari' => $hari,
            'jadwalDikelompokkan' => $jadwalDikelompokkan,
            'semuaKelas' => $semuaKelas,
            'teamDosen' => $teamDosen,
            'input' => $request->all()
        ]);
    }

    public function dashboard()
    {
        return view('karyawan.dashboard');
    }

    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tahun_akademik' => 'required|string|max:10',
            'prodi_id' => 'required|string|max:20',
            'program_id' => 'required|string|max:20',
            'kapasitas' => 'required|integer'
        ]);

        DB::table('kelas')->insert([
            'Nama' => $request->nama_kelas,
            'TahunID' => $request->tahun_akademik,
            'ProdiID' => $request->prodi_id,
            'ProgramID' => $request->program_id,
            'KapasitasMaksimum' => $request->kapasitas,
            'KapasitasSekarang' => 0,
            'LoginBuat' => Auth::guard('karyawan')->user()->KaryawanID,
            'TanggalBuat' => now(),
            'NA' => 'N'
        ]);

        $filterParams = [
            'tahun_id' => $request->filter_tahun_id,
            'program_id' => $request->filter_program_id,
            'prodi_id' => $request->filter_prodi_id,
        ];

        return redirect()->route('karyawan.jadwal.index', $filterParams)->with('success', 'Kelas berhasil ditambahkan!');
    }
    
    public function searchRuang(Request $request)
    {
        $query = $request->get('query');
        $data = DB::table('ruang')
            ->where(function($q) use ($query) {
                $q->where('RuangID', 'LIKE', "%{$query}%")
                ->orWhere('Nama', 'LIKE', "%{$query}%");
            })
            ->where('NA', 'N')
            ->limit(10)
            ->get();
        return response()->json($data);
    }

    public function searchMatakuliah(Request $request)
    {
        $query = $request->get('query');
        $prodiId = $request->get('prodi_id');

        $data = DB::table('mk')
            ->where('ProdiID', $prodiId)
            ->where(function($q) use ($query) {
                $q->where('MKKode', 'LIKE', "%{$query}%")
                ->orWhere('Nama', 'LIKE', "%{$query}%");
            })
            ->where('NA', 'N')
            ->limit(10)
            ->get();
        return response()->json($data);
    }

    public function searchDosen(Request $request)
    {
        $query = $request->get('query');
        $prodiId = $request->get('prodi_id');

        $dataQuery = DB::table('dosen')
            ->where(function($q) use ($query) {
                $q->where('Login', 'LIKE', "%{$query}%")
                ->orWhere('Nama', 'LIKE', "%{$query}%");
            })
            ->where('NA', 'N');

        if ($prodiId) {
            $dataQuery->where('ProdiID', 'like', '%.' . $prodiId . '.%');
        }

        $data = $dataQuery->limit(10)->get();
            
        return response()->json($data);
    }
    
    public function storeJadwal(Request $request)
    {
        $request->validate([
            'mk_id' => 'required|integer',
            'dosen_id' => 'required|string',
            'kelas_id' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $matakuliah = DB::table('mk')->where('MKID', $request->mk_id)->first();
            $loginBuat = Auth::guard('karyawan')->user()->Login;

            $dataJadwal = [
                'TahunID'           => $request->filter_tahun_id,
                'ProdiID'           => $request->filter_prodi_id,
                'ProgramID'         => $request->filter_program_id,
                'MKID'              => $request->mk_id,
                'MKKode'            => $matakuliah->MKKode,
                'Nama'              => $matakuliah->Nama,
                'SKS'               => $matakuliah->SKS,
                'DosenID'           => $request->dosen_id, 
                'HariID'            => $request->hari_id,
                'JamMulai'          => $request->jam_mulai,
                'JamSelesai'        => $request->jam_selesai,
                'RuangID'           => $request->ruang_id,
                'Kapasitas'         => $request->kapasitas,
                'NamaKelas'         => $request->kelas_id,
                'RencanaKehadiran'  => $request->rencana_kehadiran,
                'MaxAbsen'          => $request->max_absen,
                'TglMulai'          => $request->tanggal_mulai,
                'TglSelesai'        => $request->tanggal_selesai,
                'LoginBuat'         => $loginBuat,
                'TglBuat'           => now(),
                'NoSurat'           => '', 
                'TglSurat'          => now(), 
            ];

            DB::table('jadwal')->insert($dataJadwal);

            DB::commit(); 
        } catch (Exception $e) {
            DB::rollBack(); 
            return redirect()->back()
                ->with('error', 'Gagal menyimpan jadwal: ' . $e->getMessage())
                ->withInput();
        }
        
        $filterParams = [
            'tahun_id' => $request->filter_tahun_id,
            'program_id' => $request->filter_program_id,
            'prodi_id' => $request->filter_prodi_id,
        ];

        return redirect()->route('karyawan.jadwal.index', $filterParams)->with('success', 'Jadwal baru berhasil dibuat!');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'program_id' => 'required|string',
            'hari_id' => 'required|integer',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruang_id' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:0',
            'mk_id' => 'required|integer|exists:mk,MKID',
            'dosen_id' => 'required|string|exists:dosen,Login',
            'kelas_id' => 'required|integer|exists:kelas,KelasID',
            'rencana_kehadiran' => 'required|integer|min:0',
            'max_absen' => 'required|integer|min:0',
        ]);
    
        DB::beginTransaction();
        try {
            $matakuliah = DB::table('mk')->where('MKID', $request->mk_id)->first();
    
            $dataToUpdate = [
                'ProgramID' => $request->program_id,
                'MKID' => $request->mk_id,
                'MKKode' => $matakuliah->MKKode,
                'Nama' => $matakuliah->Nama,
                'SKS' => $matakuliah->SKS,
                'DosenID' => $request->dosen_id,
                'HariID' => $request->hari_id,
                'JamMulai' => $request->jam_mulai,
                'JamSelesai' => $request->jam_selesai,
                'TglMulai' => $request->tanggal_mulai,
                'TglSelesai' => $request->tanggal_selesai,
                'RuangID' => $request->ruang_id,
                'Kapasitas' => $request->kapasitas,
                'NamaKelas' => $request->kelas_id,
                'RencanaKehadiran' => $request->rencana_kehadiran,
                'MaxAbsen' => $request->max_absen,
                'AdaResponsi' => $request->has('ada_responsi') ? 'Y' : 'N',
                'BiayaKhusus' => $request->has('ada_biaya') ? 'Y' : 'N',
                'Biaya' => $request->has('ada_biaya') ? $request->biaya : 0,
                'NamaBiaya' => $request->has('ada_biaya') ? $request->nama_biaya : null,
                'LoginEdit' => Auth::guard('karyawan')->user()->Login,
                'TglEdit' => now(),
            ];
    
            DB::table('jadwal')->where('JadwalID', $id)->update($dataToUpdate);
    
            DB::commit();
    
            $jadwal = DB::table('jadwal')->where('JadwalID', $id)->first();
            $filterParams = [
                'tahun_id' => $jadwal->TahunID,
                'program_id' => $jadwal->ProgramID,
                'prodi_id' => $jadwal->ProdiID,
            ];
    
            return redirect()->route('karyawan.jadwal.index', $filterParams)->with('success', 'Jadwal berhasil diperbarui!');
    
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal update jadwal: ' . $e->getMessage())->withInput();
        }
    }

    public function getJadwalJson($id)
    {
        $jadwal = DB::table('jadwal as j')
            ->leftJoin('mk', 'j.MKID', '=', 'mk.MKID')
            ->leftJoin('dosen as d', 'j.DosenID', '=', 'd.Login')
            ->where('j.JadwalID', $id)
            ->select(
                'j.*',
                'mk.Nama as NamaMK', 'mk.MKKode', 'mk.SKS',
                'd.Nama as NamaDosen', 'd.Gelar',
                'j.NamaKelas as KelasID'
            )
            ->first();

        if ($jadwal) {
            return response()->json($jadwal);
        }
        return response()->json(['error' => 'Jadwal tidak ditemukan'], 404);
    }
    
    public function editDosen($id)
    {
        $jadwal = DB::table('jadwal')
            ->where('JadwalID', $id)
            ->select('JadwalID', 'Nama as NamaMK', 'MKKode', 'HariID', 'JamMulai', 'JamSelesai', 'DosenID')
            ->first();

        if (!$jadwal) {
            return response()->json(['error' => 'Jadwal tidak ditemukan'], 404);
        }

        // Ambil TIM DOSEN (yang bukan dosen utama) dari 'jadwaldosen'
        $timDosen = DB::table('jadwaldosen as jd')
            ->join('dosen as d', 'jd.DosenID', '=', 'd.Login')
            ->where('jd.JadwalID', $id)
            ->select('jd.DosenID', 'd.Nama', 'd.Gelar', 'jd.JenisDosenID')
            ->get();
            
        // Ambil data DOSEN UTAMA dari tabel 'dosen'
        $dosenUtama = null;
        if ($jadwal->DosenID) {
            $dosenUtama = DB::table('dosen')
                ->where('Login', $jadwal->DosenID)
                ->select('Login as DosenID', 'Nama', 'Gelar')
                ->first();
            if ($dosenUtama) {
                $dosenUtama->JenisDosenID = 'DSN'; 
            }
        }

        $semuaDosen = $timDosen->prepend($dosenUtama)->filter();

        $hariMap = [ 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu' ];
        $jadwal->Hari = $hariMap[$jadwal->HariID] ?? 'Tidak Diketahui';

        return response()->json([
            'jadwal' => $jadwal,
            'timDosen' => $semuaDosen,
        ]);
    }

    public function updateDosen(Request $request, $id)
    {
        $request->validate([
            'dosen_ids' => 'sometimes|array',
            'dosen_ids.*' => 'string',
            'dosen_utama_id' => 'nullable|string',
        ]);

        $dosenIds = $request->input('dosen_ids', []);
        $dosenUtamaId = $request->dosen_utama_id;
        $loginBuat = Auth::guard('karyawan')->user()->Login;

        if (count($dosenIds) > 0 && is_null($dosenUtamaId)) {
            return response()->json(['error' => 'Jika ada dosen dalam tim, salah satu harus menjadi dosen utama.'], 422);
        }

        DB::beginTransaction();
        try {
            // Set/Update Dosen Utama di tabel 'jadwal'
            DB::table('jadwal')->where('JadwalID', $id)->update(['DosenID' => $dosenUtamaId]);

            // Hapus semua tim dosen lama dari 'jadwaldosen' untuk jadwal ini
            DB::table('jadwaldosen')->where('JadwalID', $id)->delete();

            // Filter untuk mendapatkan ID tim dosen (semua ID kecuali dosen utama)
            $teamDosenIds = array_filter($dosenIds, function($dosenId) use ($dosenUtamaId) {
                return $dosenId != $dosenUtamaId;
            });

            // Jika ada anggota tim, insert mereka ke 'jadwaldosen'
            if (!empty($teamDosenIds)) {
                $dataToInsert = [];
                foreach ($teamDosenIds as $dosenId) {
                    $dataToInsert[] = [
                        'JadwalID' => $id,
                        'DosenID' => $dosenId,
                        'JenisDosenID' => 'DSC', 
                        'TglBuat' => now(),
                        'LoginBuat' => $loginBuat,
                    ];
                }
                DB::table('jadwaldosen')->insert($dataToInsert);
            }
            
            DB::commit();

            return response()->json(['success' => 'Tim Dosen berhasil diperbarui!']);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal memperbarui tim dosen: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            DB::table('jadwaldosen')->where('JadwalID', $id)->delete();
            DB::table('jadwal')->where('JadwalID', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Jadwal berhasil dihapus!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }

    public function cetak($id){ return "Cetak Jadwal ID: $id"; }
}
