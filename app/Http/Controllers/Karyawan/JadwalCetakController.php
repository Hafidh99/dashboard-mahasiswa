<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Jadwal;
use App\Models\Kelas; 

class JadwalCetakController extends Controller
{
    public function jadwalKeseluruhan(Request $request)
    {
        if (!$request->filled('tahun_id') || !$request->filled('prodi_id') || !$request->filled('program_id')) {
            return redirect()->route('karyawan.jadwal.index')->with('error', 'Silakan pilih Tahun Akademik, Program Studi, dan Program Pendidikan terlebih dahulu.');
        }

        $tahun = DB::table('tahun')->where('TahunID', $request->tahun_id)->first();
        $prodi = DB::table('prodi')->where('ProdiID', $request->prodi_id)->first();
        $program = DB::table('program')->where('ProgramID', $request->program_id)->first();
        $hari = [ 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu' ];

        $query = DB::table('jadwal as j')
            ->leftJoin('dosen as d', 'j.DosenID', '=', 'd.Login')
            ->join('kelas as k', 'j.NamaKelas', '=', 'k.KelasID') 
            ->leftJoin('ruang as r', 'j.RuangID', '=', 'r.RuangID')
            ->where('j.NA', 'N')
            ->where('j.TahunID', $request->tahun_id)
            ->where('j.ProdiID', $request->prodi_id)
            ->where('j.ProgramID', $request->program_id);
        
        if ($request->filled('hari_id')) {
            $query->where('j.HariID', $request->hari_id);
        }
        if ($request->filled('kelas_id')) {
            $query->where('j.NamaKelas', $request->kelas_id);
        }

        $jadwal = $query->select(
                'j.HariID', 'j.JamMulai', 'j.JamSelesai', 'j.MKKode', 'j.Nama', 'j.SKS',
                'd.Nama as NamaDosen', 'd.Gelar',
                'k.Nama as NamaKelas', 'j.RuangID', 'j.JumlahMhswKRS as Mhs'
            )
            ->orderBy('j.HariID', 'asc')
            ->orderBy('j.JamMulai', 'asc')
            ->get();
        
        $jadwalDikelompokkan = $jadwal->groupBy('HariID');

        return view('karyawan.jadwal.cetak.jadwal_keseluruhan', [
            'jadwalDikelompokkan' => $jadwalDikelompokkan,
            'tahun' => $tahun,
            'prodi' => $prodi,
            'program' => $program,
            'hari' => $hari,
            'input' => $request->all(),
        ]);
    }

    public function frs(Request $request)
    {
        if (!$request->filled('tahun_id') || !$request->filled('prodi_id') || !$request->filled('program_id')) {
            return redirect()->route('karyawan.jadwal.index')->with('error', 'Silakan pilih Tahun Akademik, Program Studi, dan Program Pendidikan untuk mencetak FRS.');
        }

        $tahun = DB::table('tahun')->where('TahunID', $request->tahun_id)->first();
        $prodi = DB::table('prodi')->where('ProdiID', $request->prodi_id)->first();
        $program = DB::table('program')->where('ProgramID', $request->program_id)->first();
        $hari = [ 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu' ];

        $jadwal = DB::table('jadwal as j')
            ->leftJoin('dosen as d', 'j.DosenID', '=', 'd.Login')
            ->join('kelas as k', 'j.NamaKelas', '=', 'k.KelasID') 
            ->where('j.NA', 'N')
            ->where('j.TahunID', $request->tahun_id)
            ->where('j.ProdiID', $request->prodi_id)
            ->where('j.ProgramID', $request->program_id)
            ->select(
                'j.MKKode', 'j.Nama', 'j.SKS',
                'd.Nama as NamaDosen', 'd.Gelar',
                'j.HariID', 'j.JamMulai', 'j.JamSelesai',
                'k.Nama as NamaKelas', 'j.RuangID'
            )
            ->orderBy('j.MKKode', 'asc')
            ->get();

        return view('karyawan.jadwal.cetak.frs', [
            'jadwal' => $jadwal,
            'tahun' => $tahun,
            'prodi' => $prodi,
            'program' => $program,
            'hari' => $hari,
            'input' => $request->all(),
        ]);
    }

    public function jadwalDosen(Request $request)
    {
        if (!$request->filled('tahun_id') || !$request->filled('prodi_id') || !$request->filled('program_id')) {
            return redirect()->route('karyawan.jadwal.index')->with('error', 'Silakan pilih Tahun Akademik, Program Studi, dan Program Pendidikan terlebih dahulu.');
        }

        $tahun = $request->tahun_id;
        $hari = [ 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu' ];

        $jadwalTim = DB::table('jadwaldosen as jd')
            ->join('jadwal as j', 'jd.JadwalID', '=', 'j.JadwalID')
            ->join('dosen as d', 'jd.DosenID', '=', 'd.Login')
            ->join('kelas as k', 'j.NamaKelas', '=', 'k.KelasID')
            ->where('j.TahunID', $request->tahun_id)
            ->where('j.ProdiID', $request->prodi_id)
            ->where('j.ProgramID', $request->program_id)
            ->where('j.NA', 'N')
            ->select(
                'd.Login as DosenID', 'd.Nama as NamaDosen', 'd.Gelar', 'd.NIDN',
                'j.ProgramID', 'j.HariID', 'j.JamMulai', 'j.JamSelesai', 'j.MKKode',
                'j.Nama as NamaMK', 'j.SKS', 'k.Nama as NamaKelas', 'j.RuangID'
            );

        $jadwalUtama = DB::table('jadwal as j')
            ->join('dosen as d', 'j.DosenID', '=', 'd.Login')
            ->join('kelas as k', 'j.NamaKelas', '=', 'k.KelasID')
            ->where('j.TahunID', $request->tahun_id)
            ->where('j.ProdiID', $request->prodi_id)
            ->where('j.ProgramID', $request->program_id)
            ->where('j.NA', 'N')
            ->select(
                'd.Login as DosenID', 'd.Nama as NamaDosen', 'd.Gelar', 'd.NIDN',
                'j.ProgramID', 'j.HariID', 'j.JamMulai', 'j.JamSelesai', 'j.MKKode',
                'j.Nama as NamaMK', 'j.SKS', 'k.Nama as NamaKelas', 'j.RuangID'
            )
            ->union($jadwalTim);

        $semuaJadwal = $jadwalUtama
            ->orderBy('NamaDosen', 'asc')
            ->orderBy('HariID', 'asc')
            ->orderBy('JamMulai', 'asc')
            ->get();

        $jadwalPerDosen = $semuaJadwal->groupBy('DosenID');
        
        return view('karyawan.jadwal.cetak.jadwal_dosen', [
            'jadwalPerDosen' => $jadwalPerDosen,
            'tahun' => $tahun,
            'hari' => $hari,
        ]);
    }
    
    public function jadwalPerRuang(Request $request)
    {
        if (!$request->filled('tahun_id')) {
            return redirect()->route('karyawan.jadwal.index')->with('error', 'Silakan pilih Tahun Akademik terlebih dahulu.');
        }

        $tahun = $request->tahun_id;
        $hari = [ 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu' ];

        $query = DB::table('jadwal as j')
            ->join('kelas as k', 'j.NamaKelas', '=', 'k.KelasID')
            ->where('j.TahunID', $request->tahun_id)
            ->where('j.NA', 'N')
            ->whereNotNull('j.RuangID')
            ->where('j.RuangID', '!=', '');

        if ($request->filled('prodi_id')) {
            $query->where('j.ProdiID', $request->prodi_id);
        }
        if ($request->filled('program_id')) {
            $query->where('j.ProgramID', $request->program_id);
        }

        $semuaJadwal = $query->select(
                'j.RuangID', 'j.HariID', 'j.JamMulai', 'j.JamSelesai', 'j.MKKode',
                'j.Nama as NamaMK', 'j.SKS', 'k.Nama as NamaKelas'
            )
            ->orderBy('j.RuangID', 'asc')
            ->orderBy('j.HariID', 'asc')
            ->orderBy('j.JamMulai', 'asc')
            ->get();
        
        $jadwalPerRuang = $semuaJadwal->groupBy('RuangID');

        return view('karyawan.jadwal.cetak.jadwal_per_ruang', [
            'jadwalPerRuang' => $jadwalPerRuang,
            'tahun' => $tahun,
            'hari' => $hari,
        ]);
    }
    
    public function daftarHadir(Jadwal $jadwal)
    {
        $jadwal->load('prodi', 'kelas', 'mk');

        // Mengambil daftar tim dosen
        $timDosen = DB::table('jadwaldosen as jd')
            ->join('dosen as d', 'jd.DosenID', '=', 'd.Login')
            ->where('jd.JadwalID', $jadwal->JadwalID)
            ->select('d.Nama', 'd.Gelar')
            ->orderBy('jd.JenisDosenID', 'asc')
            ->get();

        if ($timDosen->isEmpty() && $jadwal->dosen) {
            $timDosen = collect([$jadwal->dosen]);
        }
        
        // Mengambil daftar mahasiswa 
        $mahasiswa = DB::table('krs')
            ->join('mhsw', 'krs.MhswID', '=', 'mhsw.MhswID')
            ->where('krs.JadwalID', $jadwal->JadwalID)
            ->where('krs.NA', 'N')
            ->select('mhsw.MhswID as NIM', 'mhsw.Nama as NamaMahasiswa')
            ->orderBy('mhsw.MhswID', 'asc')
            ->get();

        return view('karyawan.jadwal.cetak.daftar_hadir', [
            'jadwal' => $jadwal, 
            'mahasiswa' => $mahasiswa,
            'timDosen' => $timDosen
        ]);
    }

    public function kursiUAS(Jadwal $jadwal)
    {
        // Memuat relasi yang dibutuhkan
        $jadwal->load('prodi', 'kelas', 'dosen', 'mk');

        // Mengambil daftar tim dosen (sama seperti di daftarHadir)
        $timDosen = DB::table('jadwaldosen as jd')
            ->join('dosen as d', 'jd.DosenID', '=', 'd.Login')
            ->where('jd.JadwalID', $jadwal->JadwalID)
            ->select('d.Nama', 'd.Gelar')
            ->orderBy('jd.JenisDosenID', 'asc')
            ->get();

        // Fallback jika tidak ada tim dosen
        if ($timDosen->isEmpty() && $jadwal->dosen) {
            $timDosen = collect([$jadwal->dosen]);
        }
        
        // Mengambil daftar mahasiswa
        $mahasiswa = DB::table('krs')
            ->join('mhsw', 'krs.MhswID', '=', 'mhsw.MhswID')
            ->where('krs.JadwalID', $jadwal->JadwalID)
            ->where('krs.NA', 'N')
            ->select('mhsw.MhswID as NIM', 'mhsw.Nama as NamaMahasiswa')
            ->orderBy('mhsw.MhswID', 'asc')
            ->get();

        return view('karyawan.jadwal.cetak.kursi_uas', [
            'jadwal' => $jadwal,
            'mahasiswa' => $mahasiswa,
            'timDosen' => $timDosen
        ]);
    }
}

