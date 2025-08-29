<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User as Mahasiswa; // Menggunakan model User sebagai Mahasiswa

class PaController extends Controller
{
    // ... (metode index() tidak berubah)
    public function index(Request $request)
    {
        $dosenId = Auth::guard('dosen')->user()->Login;
        $semuaTahun = DB::table('tahun')->select('TahunID', DB::raw('MIN(Nama) as Nama'))->groupBy('TahunID')->orderBy('TahunID', 'desc')->get();
        $tahunTerpilihId = $request->input('tahun_id');
        $tahunInfo = DB::table('tahun')->where('TahunID', $tahunTerpilihId)->first();

        if ($tahunTerpilihId && $tahunInfo) {
            $tahunAktif = $tahunInfo;
        } else {
            $tahunAktif = DB::table('tahun')->where('NA', 'N')->first();
            $tahunTerpilihId = $tahunAktif ? $tahunAktif->TahunID : null;
        }

        $mahasiswaBimbingan = [];
        if ($tahunAktif) {
            $mahasiswaBimbingan = DB::table('mhsw as m')
                ->join('khs as h', 'm.MhswID', '=', 'h.MhswID')
                ->join('prodi as p', 'm.ProdiID', '=', 'p.ProdiID')
                ->where('m.PenasehatAkademik', $dosenId)
                ->where('h.TahunID', $tahunAktif->TahunID)
                ->select('m.Nama', 'm.MhswID as NIM', 'p.Nama as NamaProdi', 'h.KHSID')
                ->get();
            
            foreach ($mahasiswaBimbingan as $mahasiswa) {
                $isApproved = DB::table('krs')->where('KHSID', $mahasiswa->KHSID)->where('aprv_pa', 'Y')->exists();
                $mahasiswa->Status = $isApproved ? 'Y - Disetujui' : 'N - Belum Disetujui';
            }
        }

        return view('dosen.list-pa', [
            'mahasiswaBimbingan' => $mahasiswaBimbingan,
            'tahunAktif' => $tahunAktif,
            'semuaTahun' => $semuaTahun,
            'tahunTerpilihId' => $tahunTerpilihId,
        ]);
    }

    /**
     * Menampilkan halaman detail KRS mahasiswa untuk diproses.
     */
    public function showKrsMahasiswa($khsId)
    {
        $khs = DB::table('khs')->where('KHSID', $khsId)->first();
        if (!$khs) { abort(404); }

        $mahasiswa = Mahasiswa::with('prodi', 'pembimbingAkademik')->find($khs->MhswID);
        $dosenLogin = Auth::guard('dosen')->user();

        if ($mahasiswa->PenasehatAkademik != $dosenLogin->Login) {
            abort(403, 'Akses Ditolak');
        }

        // Mengambil detail KRS untuk semester yang sedang diproses
        $krsDetail = DB::table('krs')
            ->join('jadwal', 'krs.JadwalID', '=', 'jadwal.JadwalID')
            ->leftJoin('dosen', 'jadwal.DosenID', '=', 'dosen.Login')
            ->where('krs.KHSID', $khsId)
            ->select('krs.*', 'jadwal.NamaKelas as Kelas', 'dosen.Nama as NamaDosen', 'dosen.Gelar', 'jadwal.RuangID', 'jadwal.HariID', 'jadwal.JamMulai', 'jadwal.JamSelesai')
            ->get();

        $totalSks = $krsDetail->sum('SKS');
        $isApproved = $krsDetail->isNotEmpty() && $krsDetail->first()->aprv_pa == 'Y';

        // =================================================================
        // PERBAIKAN: Mengadopsi logika dari DashboardController
        // =================================================================

        // 1. Mengambil data riwayat studi untuk perhitungan grafik
        $riwayatStudi = DB::table('krs')
            ->join('khs', 'krs.KHSID', '=', 'khs.KHSID')
            ->where('krs.MhswID', $mahasiswa->MhswID)
            ->where('krs.NA', 'N') 
            ->select(
                'khs.Sesi', 
                'khs.TahunID',
                DB::raw('SUM(krs.SKS) as total_sks_semester'),
                DB::raw('SUM(krs.SKS * krs.BobotNilai) as total_bobot_semester')
            )
            ->groupBy('khs.Sesi', 'khs.TahunID')
            ->orderBy('khs.Sesi', 'asc')
            ->get();

        // 2. Menghitung data untuk grafik (IPS dan IPK)
        $labels = [];
        $dataIPS = [];
        $dataIPK = [];
        $kumulatifSks = 0;
        $kumulatifBobot = 0;

        foreach ($riwayatStudi as $semester) {
            $labels[] = "Smt " . $semester->Sesi;
            $ips = ($semester->total_sks_semester > 0) ? ($semester->total_bobot_semester / $semester->total_sks_semester) : 0;
            $dataIPS[] = number_format($ips, 2);
            
            $kumulatifSks += $semester->total_sks_semester;
            $kumulatifBobot += $semester->total_bobot_semester;
            $ipk = ($kumulatifSks > 0) ? ($kumulatifBobot / $kumulatifSks) : 0;
            $dataIPK[] = number_format($ipk, 2);
        }
        $ipkFinal = ($kumulatifSks > 0) ? number_format($kumulatifBobot / $kumulatifSks, 2) : 0;

        // 3. Mengambil data riwayat KHS untuk tabel keuangan
        $riwayatKeuangan = DB::table('khs')->where('MhswID', $mahasiswa->MhswID)->orderBy('Sesi', 'asc')->get();

        return view('dosen.proses-krs', [
            'mahasiswa' => $mahasiswa,
            'khs' => $khs,
            'krsDetail' => $krsDetail,
            'totalSks' => $totalSks,
            'isApproved' => $isApproved,
            'riwayatKeuangan' => $riwayatKeuangan,
            'riwayatStudi' => $riwayatStudi, // <-- Mengirim data riwayat studi ke view
            'labels' => $labels,
            'dataIPS' => $dataIPS,
            'dataIPK' => $dataIPK,
            'ipk' => $ipkFinal,
        ]);
    }

    /**
     * Memproses persetujuan atau penolakan KRS.
     */
    public function processKrs(Request $request, $khsId)
    {
        $request->validate([
            'status' => 'required|in:Y,N',
            'komentar' => 'nullable|string',
        ]);

        DB::table('krs')
            ->where('KHSID', $khsId)
            ->update([
                'aprv_pa' => $request->status,
                'Komentar' => $request->komentar,
                'tgl_aprovePA' => now()
            ]);
        
        $pesan = $request->status == 'Y' ? 'disetujui' : 'ditolak';
        return redirect()->route('dosen.pa.list')->with('status', "KRS mahasiswa berhasil {$pesan}.");
    }
}
