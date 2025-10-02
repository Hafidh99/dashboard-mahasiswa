<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalNgajarDosen as Jadwal;
use PDF; 

class LaporanController extends Controller
{
    public function rekapAbsenMahasiswa(Jadwal $jadwal)
    {
        // 1. Ambil detail jadwal
        $detailJadwal = DB::table('jadwal as j')
            ->join('prodi as p', 'j.ProdiID', '=', 'p.ProdiID')
            ->join('dosen as d', 'j.DosenID', '=', 'd.Login')
            ->where('j.JadwalID', $jadwal->JadwalID)
            ->select('j.Nama as NamaMataKuliah', 'p.Nama as NamaProdi', 'j.NamaKelas', 'j.TahunID', 'd.Nama as NamaDosen', 'd.Gelar')
            ->first();

        // 2. Ambil daftar mahasiswa di kelas tersebut
        $mahasiswa = DB::table('krs as k')
            ->join('mhsw as m', 'k.MhswID', '=', 'm.MhswID')
            ->where('k.JadwalID', $jadwal->JadwalID)
            ->where('k.TahunID', $jadwal->TahunID)
            ->select('m.MhswID', 'm.Nama')
            ->orderBy('m.MhswID', 'asc')
            ->get();

        // 3. Ambil semua pertemuan yang ada
        $pertemuan = DB::table('presensi')
            ->where('JadwalID', $jadwal->JadwalID)
            ->orderBy('Pertemuan', 'asc')
            ->get();

        // 4. Ambil semua data absensi dan kelompokkan agar mudah dicari
        $absensi = DB::table('presensimhsw')
            ->where('JadwalID', $jadwal->JadwalID)
            ->get()
            ->keyBy(function ($item) {
                return $item->MhswID . '_' . $item->PresensiID;
            });

        // 5. Buat PDF
        $pdf = PDF::loadView('dosen.laporan.rekap_absen_mahasiswa', compact('detailJadwal', 'mahasiswa', 'pertemuan', 'absensi'));

        $pdf->setPaper('a4', 'landscape');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="rekap-absen-mahasiswa.pdf"',
        ]);
    }

    public function rekapPresensiDosen(Jadwal $jadwal)
    {
        // 1. Ambil detail jadwal
        $detailJadwal = DB::table('jadwal as j')
            ->join('prodi as p', 'j.ProdiID', '=', 'p.ProdiID')
            ->join('dosen as d', 'j.DosenID', '=', 'd.Login')
            ->where('j.JadwalID', $jadwal->JadwalID)
            ->select('j.Nama as NamaMataKuliah', 'p.Nama as NamaProdi', 'j.NamaKelas', 'j.TahunID', 'd.Nama as NamaDosen', 'd.Gelar')
            ->first();

        // 2. Ambil semua pertemuan yang ada
        $daftarPertemuan = DB::table('presensi')
            ->where('JadwalID', $jadwal->JadwalID)
            ->orderBy('Pertemuan', 'asc')
            ->get();

        // 3. Buat PDF
        $pdf = PDF::loadView('dosen.laporan.rekap_presensi_dosen', compact('detailJadwal', 'daftarPertemuan'));
        
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="rekapan-presensi-dosen.pdf"',
        ]);
    }

    /* Membuat PDF Nilai Akhir Mahasiswa (Simple). */
    public function cetakNilai(Jadwal $jadwal)
    {
        $detailJadwal = $this->getDetailJadwal($jadwal);
        $mahasiswa = $this->getMahasiswaNilai($jadwal, false); // false = simple

        $pdf = PDF::loadView('dosen.laporan.cetak_nilai', compact('detailJadwal', 'mahasiswa'));
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="cetak-nilai.pdf"',
        ]);
    }

    /* Membuat PDF  */
    public function cetakDetailNilai(Jadwal $jadwal)
    {
        $detailJadwal = $this->getDetailJadwal($jadwal);
        $mahasiswa = $this->getMahasiswaNilai($jadwal, true); 

        $pdf = PDF::loadView('dosen.laporan.cetak_detail_nilai', compact('detailJadwal', 'mahasiswa'));
        $pdf->setPaper('a4', 'landscape'); 
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="cetak-detail-nilai.pdf"',
        ]);
    }

    private function getDetailJadwal(Jadwal $jadwal)
    {
        return DB::table('jadwal as j')
            ->join('prodi as p', 'j.ProdiID', '=', 'p.ProdiID')
            ->join('dosen as d', 'j.DosenID', '=', 'd.Login')
            ->where('j.JadwalID', $jadwal->JadwalID)
            ->select('j.Nama as NamaMataKuliah', 'p.Nama as NamaProdi', 'j.NamaKelas', 'j.TahunID', 'd.Nama as NamaDosen', 'd.Gelar')
            ->first();
    }

    private function getMahasiswaNilai(Jadwal $jadwal, $isDetail = false)
    {
        $query = DB::table('krs as k')
            ->join('mhsw as m', 'k.MhswID', '=', 'm.MhswID')
            ->where('k.JadwalID', $jadwal->JadwalID)
            ->where('k.TahunID', $jadwal->TahunID);

        if ($isDetail) {
            $query->select('m.MhswID', 'm.Nama', 'k.Presensi', 'k.Tugas1', 'k.Tugas2', 'k.Tugas3', 'k.UTS', 'k.UAS', 'k.NilaiAkhir', 'k.GradeNilai as Grade');
        } else {
            $query->select('m.MhswID', 'm.Nama', 'k.NilaiAkhir', 'k.GradeNilai as Grade');
        }

        return $query->orderBy('m.MhswID', 'asc')->get();
    }
}

