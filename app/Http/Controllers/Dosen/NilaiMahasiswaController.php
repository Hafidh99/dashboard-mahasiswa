<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JadwalNgajarDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NilaiMahasiswaController extends Controller
{
    /**
     * Menampilkan halaman form untuk input nilai mahasiswa.
     */
    public function edit(JadwalNgajarDosen $jadwal)
    {
        $detailJadwal = DB::table('jadwal as j')
            ->join('prodi as p', 'j.ProdiID', '=', 'p.ProdiID')
            ->where('j.JadwalID', $jadwal->JadwalID)
            ->select('j.Nama as NamaMataKuliah', 'p.Nama as NamaProdi', 'j.NamaKelas', 'j.TahunID', 'j.HariID', 'j.JamMulai', 'j.JamSelesai')
            ->first();

        // Mengambil daftar mahasiswa yang mengambil mata kuliah ini dari tabel krs
        // Bergabung dengan tabel mhsw untuk mendapatkan nama mahasiswa
        $krs_mahasiswa = DB::table('krs')
            ->join('mhsw', 'krs.MhswID', '=', 'mhsw.MhswID')
            ->where('krs.JadwalID', $jadwal->JadwalID)
            ->select('krs.*', 'mhsw.Nama as NamaMahasiswa')
            ->orderBy('mhsw.MhswID', 'asc')
            ->get();

        $hari = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];

        return view('dosen.input_nilai', [
            'jadwal'       => $jadwal,
            'detailJadwal' => $detailJadwal,
            'krs'          => $krs_mahasiswa,
            'hari'         => $hari,
        ]);
    }


    /**
     * Menyimpan (update) data nilai mahasiswa ke database.
     */
    public function update(Request $request, JadwalNgajarDosen $jadwal)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $inputNilai = $request->nilai;
        $bobot = $jadwal; 

        DB::beginTransaction();
        try {
            foreach ($inputNilai as $krsId => $nilai) {
                
                $nilai_akhir = 0;
                
                $nilai_akhir += (($nilai['Tugas1'] ?? 0)   * ($bobot->Tugas1 ?? 0) / 100);
                $nilai_akhir += (($nilai['Tugas2'] ?? 0)   * ($bobot->Tugas2 ?? 0) / 100);
                $nilai_akhir += (($nilai['Tugas3'] ?? 0)   * ($bobot->Tugas3 ?? 0) / 100);
                $nilai_akhir += (($nilai['Presensi'] ?? 0) * ($bobot->Presensi ?? 0) / 100);
                $nilai_akhir += (($nilai['UTS'] ?? 0)      * ($bobot->UTS ?? 0) / 100);
                $nilai_akhir += (($nilai['UAS'] ?? 0)      * ($bobot->UAS ?? 0) / 100);
                
                $grade = 'E';
                $bobot_nilai = 0.00;
                if ($nilai_akhir >= 85) { $grade = 'A'; $bobot_nilai = 4.00; } 
                elseif ($nilai_akhir >= 80) { $grade = 'A-'; $bobot_nilai = 3.70; } 
                elseif ($nilai_akhir >= 75) { $grade = 'B+'; $bobot_nilai = 3.30; } 
                elseif ($nilai_akhir >= 70) { $grade = 'B'; $bobot_nilai = 3.00; } 
                elseif ($nilai_akhir >= 65) { $grade = 'B-'; $bobot_nilai = 2.70; } 
                elseif ($nilai_akhir >= 60) { $grade = 'C+'; $bobot_nilai = 2.30; } 
                elseif ($nilai_akhir >= 55) { $grade = 'C'; $bobot_nilai = 2.00; } 
                elseif ($nilai_akhir >= 50) { $grade = 'D'; $bobot_nilai = 1.00; } 
                else { $grade = 'E'; $bobot_nilai = 0.00; }

                DB::table('krs')->where('KRSID', $krsId)->update([
                    'Tugas1'     => $nilai['Tugas1'] ?? 0,
                    'Tugas2'     => $nilai['Tugas2'] ?? 0,
                    'Tugas3'     => $nilai['Tugas3'] ?? 0,
                    'Presensi'   => $nilai['Presensi'] ?? 0,
                    'UTS'        => $nilai['UTS'] ?? 0,
                    'UAS'        => $nilai['UAS'] ?? 0,
                    'NilaiAkhir' => $nilai_akhir,
                    'GradeNilai' => $grade,
                    'BobotNilai' => $bobot_nilai,
                    'TanggalEdit'=> now(),
                ]);
            }
            DB::commit();
            return back()->with('status', 'Nilai mahasiswa berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}

