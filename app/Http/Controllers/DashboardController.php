<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Kode Anda yang sudah ada (TIDAK DIUBAH)
        $mahasiswa = Auth::user()->load(['prodi', 'khs', 'pembimbingAkademik']);
        $riwayatStudi = DB::table('krs')
            ->join('khs', 'krs.KHSID', '=', 'khs.KHSID')
            ->where('krs.MhswID', $mahasiswa->MhswID)
            ->where('krs.NA', 'N') 
            ->select(
                'khs.Sesi', 
                DB::raw('SUM(krs.SKS) as total_sks_semester'),
                DB::raw('SUM(krs.SKS * krs.BobotNilai) as total_bobot_semester')
            )
            ->groupBy('khs.Sesi')
            ->orderBy('khs.Sesi', 'asc')
            ->get();
        $labels = [];
        $dataIPS = [];
        $dataIPK = [];
        $kumulatifSks = 0;
        $kumulatifBobot = 0;
        $dosens = DB::table('dosen')
                    ->where('NA', 'N') 
                    ->select('Login', 'Nama', 'Gelar')
                    ->orderBy('Nama')
                    ->get();
        foreach ($riwayatStudi as $semester) {
            $labels[] = $semester->Sesi;
            $ips = ($semester->total_sks_semester > 0) ? ($semester->total_bobot_semester / $semester->total_sks_semester) : 0;
            $dataIPS[] = number_format($ips, 2);
            $kumulatifSks += $semester->total_sks_semester;
            $kumulatifBobot += $semester->total_bobot_semester;
            $ipk = ($kumulatifSks > 0) ? ($kumulatifBobot / $kumulatifSks) : 0;
            $dataIPK[] = number_format($ipk, 2);
        }
        $ipkFinal = ($kumulatifSks > 0) ? number_format($kumulatifBobot / $kumulatifSks, 2) : 0;
        $semesterBerjalan = DB::table('khs')->where('MhswID', $mahasiswa->MhswID)->max('Sesi');
        $riwayatNilai = $mahasiswa->khs->sortBy('Sesi');


        return view('dashboard', [
            'mahasiswa' => $mahasiswa,
            'semesterBerjalan' => $semesterBerjalan,
            'ipk' => $ipkFinal,
            'labels' => $labels,
            'dataIPS' => $dataIPS,
            'dataIPK' => $dataIPK,
            'dosens' => $dosens,
            'riwayatNilai' => $riwayatNilai,
        ]);
    }
}