<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /**
     * Menampilkan halaman jadwal mengajar dosen yang sedang login.
     */
    public function index(Request $request)
    {
        $dosenUser = Auth::guard('dosen')->user();

        if (!$dosenUser) {
            return redirect()->route('dosen.login');
        }

        $dosenId = $dosenUser->Login;
        $namaDosen = $dosenUser->Nama;

        $semuaTahun = DB::table('tahun')
                        ->select('TahunID')
                        ->groupBy('TahunID')
                        ->orderBy('TahunID', 'desc')
                        ->get();

        $tahunIdTerpilih = $request->input('tahun_id');
        $tahunTerpilih = null;

        if ($tahunIdTerpilih) {
            $tahunTerpilih = DB::table('tahun')->where('TahunID', $tahunIdTerpilih)->first();
        } else if (!$semuaTahun->isEmpty()) {
            $tahunTerpilih = DB::table('tahun')
                                ->where('NA', 'N')
                                ->orderBy('TahunID', 'desc')
                                ->first();
            if (!$tahunTerpilih) {
                $tahunTerpilih = DB::table('tahun')->where('TahunID', $semuaTahun->first()->TahunID)->first();
            }
        }
        
        $jadwalMengajar = collect(); 
        $jadwalTim = collect(); 

        if ($tahunTerpilih) {
            // Query untuk Jadwal Dosen Utama
            $jadwalMengajar = DB::table('jadwal as j')
                ->join('prodi as p', 'j.ProdiID', '=', 'p.ProdiID')
                ->join('dosen as d', 'j.DosenID', '=', 'd.Login')
                ->where('j.DosenID', $dosenId)
                ->where('j.TahunID', $tahunTerpilih->TahunID)
                ->where('j.NA', 'N') 
                ->select('j.*', 'p.Nama as NamaProdi', 'd.Nama as NamaDosen', 'd.Gelar')
                ->orderBy('j.HariID')->orderBy('j.JamMulai')->get();

            // Query untuk Jadwal Tim Pengajar
            $jadwalTim = DB::table('jadwal as j')
                ->join('prodi as p', 'j.ProdiID', '=', 'p.ProdiID')
                ->join('dosen as d', 'j.DosenID', '=', 'd.Login') 
                ->where('j.TahunID', $tahunTerpilih->TahunID)
                ->where('j.DosenID', '!=', $dosenId) 
                ->where('j.NA', 'N') 
                ->whereExists(function ($query) use ($dosenId) {
                    $query->select(DB::raw(1))
                        ->from('jadwaldosen as jd')
                        ->whereColumn('jd.JadwalID', 'j.JadwalID')
                        ->where('jd.DosenID', $dosenId);
                })
                ->select('j.*', 'p.Nama as NamaProdi', 'd.Nama as NamaDosen', 'd.Gelar')
                ->orderBy('j.HariID')->orderBy('j.JamMulai')->get();
        }
        
        $hari = [ 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu' ];
        
        return view('dosen.jadwal', [
            'jadwalMengajar' => $jadwalMengajar,
            'jadwalTim' => $jadwalTim,
            'namaDosen' => $namaDosen,
            'hari' => $hari,
            'semuaTahun' => $semuaTahun,
            'tahunTerpilih' => $tahunTerpilih,
        ]);
    }
}

