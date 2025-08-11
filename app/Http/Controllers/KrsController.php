<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Khs; 

class KrsController extends Controller
{
    public function isi()
    {
        $mahasiswa = Auth::user();
        $today = Carbon::now();
        $tahunAktif = null;
        $bisaIsiKrs = false;
        $sudahIsiKrs = false;
        $krsDisetujui = false;
        $keuanganLunas = false;
        $sisaTagihan = 0;

        // Pengecekan Keuangan (tetap sama)
        $summaryKeuangan = DB::table('khs')->where('MhswID', $mahasiswa->MhswID)->selectRaw('SUM(Biaya) as total_biaya, SUM(Potongan) as total_potongan, SUM(Bayar) as total_bayar')->first();
        if ($summaryKeuangan) {
            $sisaTagihan = $summaryKeuangan->total_biaya - $summaryKeuangan->total_potongan - $summaryKeuangan->total_bayar;
            if ($sisaTagihan <= 0) {
                $keuanganLunas = true;
            }
        }

        // Cari tahun akademik yang aktif (tetap sama)
        $tahunAktif = DB::table('tahun')->where('ProdiID', $mahasiswa->ProdiID)->where('NA', 'N')->first();

        if ($tahunAktif) {
            // Cek periode pengisian KRS (tetap sama)
            $tglMulai = Carbon::parse($tahunAktif->TglKRSMulai);
            $tglSelesai = Carbon::parse($tahunAktif->TglKRSSelesai)->endOfDay();
            if ($today->between($tglMulai, $tglSelesai)) {
                $bisaIsiKrs = true;
            }

            // Cek Status KRS & Persetujuan 
            $khs = DB::table('khs')->where('MhswID', $mahasiswa->MhswID)->where('TahunID', 'like', $tahunAktif->TahunID . '%')->first();
            if ($khs) {
                // Cek apakah ada MK yang sudah disetujui ('Y')
                $krsDisetujui = DB::table('krs')
                                ->where('KHSID', $khs->KHSID)
                                ->where('aprv_pa', 'Y')
                                ->exists();

                // Cek apakah sudah ada MK yang diambil (meski belum disetujui)
                $jumlahMKDiambil = DB::table('krs')->where('KHSID', $khs->KHSID)->count();
                if ($jumlahMKDiambil > 0) {
                    $sudahIsiKrs = true;
                }
            }
            
            // Final check: Batalkan semua izin jika keuangan belum lunas atau KRS sudah disetujui
            if (!$keuanganLunas || $krsDisetujui) {
                $bisaIsiKrs = false;
            }
        }
        
        return view('krs.isi', [
            'tahunAktif' => $tahunAktif,
            'bisaIsiKrs' => $bisaIsiKrs,
            'sudahIsiKrs' => $sudahIsiKrs,
            'krsDisetujui' => $krsDisetujui,
            'keuanganLunas' => $keuanganLunas,
            'sisaTagihan' => $sisaTagihan,
        ]);
    }

    public function ambil()
    {
        $mahasiswa = Auth::user();

        $tahunAktif = DB::table('tahun')
                        ->where('ProdiID', $mahasiswa->ProdiID)
                        ->where('NA', 'N')
                        ->first();

        $mataKuliah = DB::table('jadwal')
                        ->leftJoin('dosen', 'jadwal.DosenID', '=', 'dosen.Login')
                        ->where('jadwal.TahunID', $tahunAktif->TahunID)
                        ->where('jadwal.ProdiID', $mahasiswa->ProdiID)
                        ->where('jadwal.NA', 'N')
                        ->select('jadwal.*', 'dosen.Nama as NamaDosen', 'dosen.Gelar')
                        ->orderBy('jadwal.Nama')
                        ->get();

        return view('krs.ambil', [
            'tahunAktif' => $tahunAktif,
            'mataKuliah' => $mataKuliah,
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'mk_ids' => ['required', 'array'],
            'mk_ids.*' => ['integer'],
        ]);

        $mahasiswa = Auth::user();

        $tahunAktif = DB::table('tahun')
                        ->where('ProdiID', $mahasiswa->ProdiID)
                        ->where('NA', 'N')
                        ->first();

        $khs = Khs::firstOrCreate(
            [
                'MhswID' => $mahasiswa->MhswID,
                'TahunID' => $tahunAktif->TahunID,
            ],
            [
                'KodeID' => 'HTP', 
                'ProgramID' => $mahasiswa->ProgramID,
                'ProdiID' => $mahasiswa->ProdiID,
                'Sesi' => substr($tahunAktif->TahunID, 4, 1),
                'StatusMhswID' => 'A',
            ]
        );

        $jadwalDipilih = DB::table('jadwal')->whereIn('JadwalID', $request->mk_ids)->get();

        $dataUntukInsert = [];
        foreach ($jadwalDipilih as $jadwal) {
            $dataUntukInsert[] = [
                'KHSID' => $khs->KHSID,
                'MhswID' => $mahasiswa->MhswID,
                'JadwalID' => $jadwal->JadwalID,
                'TahunID' => $jadwal->TahunID,
                'MKID' => $jadwal->MKID,
                'MKKode' => $jadwal->MKKode,
                'Nama' => $jadwal->Nama,
                'SKS' => $jadwal->SKS,
                'DosenID' => $jadwal->DosenID,
                'TanggalBuat' => now(),
                'LoginBuat' => $mahasiswa->MhswID,
            ];
        }

        if (!empty($dataUntukInsert)) {
            DB::table('krs')->insert($dataUntukInsert);
        }
        
        return redirect()->route('krs.lihat')->with('status', 'KRS Berhasil Disimpan!');
    }

    public function lihat()
    {
        $mahasiswa = Auth::user()->load('prodi', 'pembimbingAkademik');
        $krsAktif = collect();
        $totalSks = 0;
        $krsDisetujui = false;

        $tahunAktif = DB::table('tahun')
                        ->where('ProdiID', $mahasiswa->ProdiID)
                        ->where('NA', 'N')
                        ->first();

        if ($tahunAktif) {
            $krsAktif = DB::table('krs')
                ->join('jadwal', 'krs.JadwalID', '=', 'jadwal.JadwalID')
                ->leftJoin('dosen', 'jadwal.DosenID', '=', 'dosen.Login')
                ->where('krs.MhswID', $mahasiswa->MhswID)
                ->where('krs.TahunID', $tahunAktif->TahunID)
                ->select(
                    'krs.MKKode',
                    'krs.Nama',
                    'krs.SKS',
                    'jadwal.NamaKelas as Kelas',
                    'dosen.Nama as NamaDosen',
                    'dosen.Gelar',
                    'jadwal.RuangID',
                    'jadwal.HariID', 
                    'jadwal.JamMulai',
                    'jadwal.JamSelesai',
                    'krs.aprv_pa',
                    'krs.tgl_aprovePA',
                    'krs.KRSID' 
                )
                ->get();

            $totalSks = $krsAktif->sum('SKS');
            $krsDisetujui = $krsAktif->contains('aprv_pa', 'Y');
    }

        return view('krs.lihat', [
            'mahasiswa' => $mahasiswa,
            'tahunAktif' => $tahunAktif,
            'krsAktif' => $krsAktif,
            'totalSks' => $totalSks,
            'krsDisetujui' => $krsDisetujui, 
        ]);
    }

    public function cetak(Request $request)
    {
        $mahasiswa = Auth::user()->load('prodi');
        $semesterList = DB::table('khs')
                        ->where('MhswID', $mahasiswa->MhswID)
                        ->orderBy('Sesi', 'desc')
                        ->pluck('Sesi');

        $selectedSemester = $request->input('semester');
        $krsDetail = collect();
        $tahunSemester = null;
        $totalSks = 0;

        if ($selectedSemester) {
            $khsInfo = DB::table('khs')
                ->where('MhswID', $mahasiswa->MhswID)
                ->where('Sesi', $selectedSemester)
                ->first();
            
            if ($khsInfo) {
                $tahunSemester = $khsInfo; 
                $krsDetail = DB::table('krs')
                    ->join('jadwal', 'krs.JadwalID', '=', 'jadwal.JadwalID')
                    ->leftJoin('dosen', 'jadwal.DosenID', '=', 'dosen.Login')
                    ->where('krs.KHSID', $khsInfo->KHSID)
                    ->select(
                        'krs.MKKode', 
                        'krs.Nama', 
                        'krs.SKS', 
                        'jadwal.NamaKelas as Kelas',
                        'dosen.Nama as NamaDosen', 
                        'dosen.Gelar', 
                        'jadwal.RuangID',
                        'jadwal.HariID', 
                        'jadwal.JamMulai', 
                        'jadwal.JamSelesai', 
                        'krs.aprv_pa',
                        'krs.tgl_aprovePA' 
                    )
                    ->get();
                
                $totalSks = $krsDetail->sum('SKS');
            }
        }

        

        return view('krs.cetak', [
            'mahasiswa' => $mahasiswa,
            'semesterList' => $semesterList,
            'selectedSemester' => $selectedSemester,
            'tahunSemester' => $tahunSemester,
            'krsDetail' => $krsDetail,
            'totalSks' => $totalSks,
        ]);
    }
    
    public function hapus($krsId)
    {
        $mahasiswa = Auth::user();
        $krsEntry = DB::table('krs')->where('KRSID', $krsId)->first();

        if ($krsEntry && $krsEntry->MhswID == $mahasiswa->MhswID) {
            DB::table('krs')->where('KRSID', $krsId)->delete();
            return redirect()->back()->with('status', 'Mata kuliah berhasil dihapus.');
        }
        return redirect()->back()->withErrors('Gagal menghapus mata kuliah.');
    }

}