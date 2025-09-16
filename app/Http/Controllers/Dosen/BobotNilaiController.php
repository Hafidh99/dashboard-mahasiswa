<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalNgajarDosen as Jadwal; 

class BobotNilaiController extends Controller
{
    /**
     * Menampilkan halaman form untuk set bobot penilaian.
     */
    public function edit($jadwalId)
    {
        $jadwal = Jadwal::findOrFail($jadwalId);

        // Ambil detail informasi jadwal yang dipilih untuk ditampilkan di header
        // Menggunakan join untuk mengambil Nama Prodi dari tabel prodi
        $detailJadwal = DB::table('jadwal as j')
            ->join('prodi as p', 'j.ProdiID', '=', 'p.ProdiID')
            ->where('j.JadwalID', $jadwal->JadwalID)
            ->select('j.Nama as NamaMataKuliah', 'p.Nama as NamaProdi', 'j.NamaKelas', 'j.TahunID', 'j.HariID', 'j.JamMulai', 'j.JamSelesai')
            ->first();

        $hari = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];

        return view('dosen.bobot_nilai', [
            'jadwal' => $jadwal, 
            'detailJadwal' => $detailJadwal,
            'hari' => $hari
        ]);
    }

    /**
     * Menyimpan (update) data bobot penilaian ke database.
     */
    public function update(Request $request, $jadwalId)
    {
        $jadwal = Jadwal::findOrFail($jadwalId);

        // 1. Validasi input
        $validatedData = $request->validate([
            'Presensi' => 'required|numeric|min:0|max:100',
            'TugasMandiri' => 'required|numeric|min:0|max:100',
            'Tugas1' => 'required|numeric|min:0|max:100',
            'Tugas2' => 'required|numeric|min:0|max:100',
            'Tugas3' => 'required|numeric|min:0|max:100',
            'soft_skill' => 'required|numeric|min:0|max:100',
            'lab' => 'required|numeric|min:0|max:100',
            'UTS' => 'required|numeric|min:0|max:100',
            'UAS' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        // 2. Validasi manual untuk memastikan total adalah 100
        $total = $request->input('Presensi') +
                $request->input('TugasMandiri') +
                $request->input('Tugas1') +
                $request->input('Tugas2') +
                $request->input('Tugas3') +
                $request->input('soft_skill') +
                $request->input('lab') +
                $request->input('UTS') +
                $request->input('UAS');

        if (abs($total - 100) > 0.01) {
            return back()->withErrors(['total' => 'Total persentase bobot penilaian harus tepat 100%. Total saat ini adalah ' . $total . '%.'])->withInput();
        }
        
        // 3. Update data di tabel jadwal
        $jadwal->update([
            'Presensi' => $validatedData['Presensi'],
            'TugasMandiri' => $validatedData['TugasMandiri'],
            'Tugas1' => $validatedData['Tugas1'],
            'Tugas2' => $validatedData['Tugas2'],
            'Tugas3' => $validatedData['Tugas3'],
            'UTS' => $validatedData['UTS'],
            'UAS' => $validatedData['UAS'],
        ]);

        return redirect()->route('dosen.jadwal.index')->with('status', 'Bobot penilaian berhasil diperbarui!');
    }
}
