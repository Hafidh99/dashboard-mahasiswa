<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KhsController extends Controller
{
    public function cetak(Request $request)
    {
        $mahasiswa = Auth::user()->load('prodi', 'pembimbingAkademik');
        $semesterList = DB::table('khs')
            ->where('MhswID', $mahasiswa->MhswID)
            ->orderBy('Sesi', 'desc')
            ->distinct()
            ->pluck('Sesi');

        $selectedSemester = $request->input('semester');
        $krsDetail = collect();
        $khsInfo = null;
        $totalSks = 0;
        $totalMutu = 0;
        $ips = 0;

        if ($selectedSemester) {
            $khsInfo = DB::table('khs')
                ->where('MhswID', $mahasiswa->MhswID)
                ->where('Sesi', $selectedSemester)
                ->first();
            
            if ($khsInfo) {
                $krsDetail = DB::table('krs')
                    ->where('KHSID', $khsInfo->KHSID)
                    ->select(
                        'MKKode', 
                        'Nama', 
                        'SKS', 
                        'GradeNilai',
                        'BobotNilai',
                        DB::raw('SKS * BobotNilai as Mutu') 
                    )
                    ->get();
                
                $totalSks = $krsDetail->sum('SKS');
                $totalMutu = $krsDetail->sum('Mutu');

                if ($totalSks > 0) {
                    $ips = $totalMutu / $totalSks;
                }
            }
        }

        return view('khs.cetak', [
            'mahasiswa' => $mahasiswa,
            'semesterList' => $semesterList,
            'selectedSemester' => $selectedSemester,
            'khsInfo' => $khsInfo,
            'krsDetail' => $krsDetail,
            'totalSks' => $totalSks,
            'totalMutu' => $totalMutu,
            'ips' => $ips,
        ]);
    }

    public function transkrip(Request $request)
    {
        $mahasiswa = Auth::user()->load('prodi');

        $semesterList = DB::table('khs')
            ->where('MhswID', $mahasiswa->MhswID)
            ->orderBy('Sesi', 'asc')
            ->distinct()
            ->pluck('Sesi');

        $semesterAwal = $request->input('semester_awal');
        $semesterAkhir = $request->input('semester_akhir');

        $transkripDetail = collect();
        $totalSksKumulatif = 0;
        $totalMutuKumulatif = 0;
        $ipk = 0;

        if ($semesterAwal && $semesterAkhir) {
            $khsIds = DB::table('khs')
                ->where('MhswID', $mahasiswa->MhswID)
                ->whereBetween('Sesi', [$semesterAwal, $semesterAkhir])
                ->pluck('KHSID');

            if ($khsIds->isNotEmpty()) {
                $transkripDetail = DB::table('krs')
                    ->join('khs', 'krs.KHSID', '=', 'khs.KHSID')
                    ->whereIn('krs.KHSID', $khsIds)
                    ->select(
                        'krs.MKKode', 
                        'krs.Nama', 
                        'krs.SKS', 
                        'krs.GradeNilai',
                        'krs.BobotNilai',
                        'khs.Sesi as Semester', 
                        DB::raw('krs.SKS * krs.BobotNilai as Mutu')
                    )
                    ->orderBy('khs.Sesi', 'asc')
                    ->orderBy('krs.MKKode', 'asc')
                    ->get();

                $totalSksKumulatif = $transkripDetail->sum('SKS');
                $totalMutuKumulatif = $transkripDetail->sum('Mutu');

                if ($totalSksKumulatif > 0) {
                    $ipk = $totalMutuKumulatif / $totalSksKumulatif;
                }
            }
        }

        return view('khs.transkrip', [
            'mahasiswa' => $mahasiswa,
            'semesterList' => $semesterList,
            'semesterAwal' => $semesterAwal,
            'semesterAkhir' => $semesterAkhir,
            'transkripDetail' => $transkripDetail,
            'totalSksKumulatif' => $totalSksKumulatif,
            'totalMutuKumulatif' => $totalMutuKumulatif,
            'ipk' => $ipk,
        ]);
    }
}
