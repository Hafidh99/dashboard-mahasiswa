<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalNgajarDosen;
use App\Models\Presensi;

class PresensiController extends Controller
{
    public function index(JadwalNgajarDosen $jadwal)
    {
        // 1. Ambil detail informasi jadwal yang dipilih
        $detailJadwal = DB::table('jadwal as j')
            ->join('prodi as p', 'j.ProdiID', '=', 'p.ProdiID')
            ->join('dosen as d', 'j.DosenID', '=', 'd.Login')
            ->where('j.JadwalID', $jadwal->JadwalID)
            ->select('j.Nama as NamaMataKuliah', 'p.Nama as NamaProdi', 'j.NamaKelas', 'j.TahunID', 'j.HariID', 'j.JamMulai', 'j.JamSelesai', 'd.Nama as NamaDosen', 'd.Gelar')
            ->first();

        // 2. Ambil semua data presensi (pertemuan) untuk jadwal ini
        $daftarPertemuan = DB::table('presensi')
            ->join('dosen', 'presensi.DosenID', '=', 'dosen.Login')
            ->where('presensi.JadwalID', $jadwal->JadwalID)
            ->select('presensi.*', 'dosen.Nama as NamaDosenPengajar')
            ->orderBy('presensi.Pertemuan', 'asc')
            ->get();
            
        // 3. Data tambahan
        $hari = [ 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu' ];

        // 4. Kirim semua data ke view
        return view('dosen.presensi.index', [
            'jadwal' => $jadwal,
            'detailJadwal' => $detailJadwal,
            'daftarPertemuan' => $daftarPertemuan,
            'hari' => $hari,
        ]);
    }

    public function store(Request $request, JadwalNgajarDosen $jadwal)
    {
        // 1. Validasi input dari form
        $request->validate([
            'pertemuan' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'catatan' => 'nullable|string',
        ]);

        // 2. Simpan data baru ke tabel 'presensi'
        DB::table('presensi')->insert([
            'JadwalID' => $jadwal->JadwalID,
            'TahunID' => $jadwal->TahunID,
            'Pertemuan' => $request->pertemuan,
            'DosenID' => Auth::guard('dosen')->id(),
            'Tanggal' => $request->tanggal,
            'JamMulai' => $request->jam_mulai,
            'JamSelesai' => $request->jam_selesai,
            'Catatan' => $request->catatan,
            'LoginBuat' => Auth::guard('dosen')->id(),
            'TanggalBuat' => now(),
            'spadasesiid' => 0,
            'TanggalEdit' => now(),
        ]);

        // 3. Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('status', 'Pertemuan berhasil ditambahkan!');
    }

    public function update(Request $request, Presensi $presensi)
    {
        // 1. Validasi input dari form
        $request->validate([
            'pertemuan' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'catatan' => 'nullable|string',
        ]);
        
        // 2. Update data di database
        $presensi->update([
            'Pertemuan' => $request->pertemuan,
            'Tanggal' => $request->tanggal,
            'JamMulai' => $request->jam_mulai,
            'JamSelesai' => $request->jam_selesai,
            'Catatan' => $request->catatan,
            'LoginEdit' => Auth::guard('dosen')->id(),
            'TanggalEdit' => now(),
        ]);
        
        // 3. Redirect ke halaman index presensi dengan pesan sukses
        return redirect()->route('dosen.jadwal.presensi.index', $presensi->JadwalID)
            ->with('status', 'Pertemuan berhasil diperbarui!');
    }

    public function destroy(Presensi $presensi)
    {
        $jadwalId = $presensi->JadwalID;
        $presensi->delete();

        return redirect()->route('dosen.jadwal.presensi.index', $jadwalId)
            ->with('status', 'Pertemuan berhasil dihapus!');
    }

    public function editAbsen(Presensi $presensi)
    {
        // 1. Ambil daftar mahasiswa yang mengambil mata kuliah ini dari tabel KRS
        $mahasiswaKelas = DB::table('krs as k')
            ->join('mhsw as m', 'k.MhswID', '=', 'm.MhswID')
            ->where('k.JadwalID', $presensi->JadwalID)
            ->where('k.TahunID', $presensi->TahunID) // <-- PERBAIKAN DI SINI
            ->select('m.MhswID', 'm.Nama')
            ->orderBy('m.MhswID', 'asc')
            ->get();

        // 2. Ambil data absensi yang sudah ada untuk pertemuan ini
        $absensiSudahAda = DB::table('presensimhsw')
            ->where('PresensiID', $presensi->PresensiID)
            ->pluck('JenisPresensiID', 'MhswID');

        // 3. Gabungkan data mahasiswa dengan data absensinya
        $daftarAbsensi = $mahasiswaKelas->map(function ($mahasiswa) use ($absensiSudahAda) {
            $mahasiswa->JenisPresensiID = $absensiSudahAda[$mahasiswa->MhswID] ?? 'H'; // Default 'H' (Hadir)
            return $mahasiswa;
        });

        // 4. Ambil daftar jenis presensi (Hadir, Izin, dll) dari tabelnya
        $jenisPresensi = DB::table('jenispresensi')->where('NA', 'N')->get();

        return view('dosen.presensi.absen', [
            'presensi' => $presensi,
            'daftarAbsensi' => $daftarAbsensi,
            'jenisPresensi' => $jenisPresensi,
        ]);
    }

    public function updateAbsen(Request $request, Presensi $presensi)
    {
        $request->validate([
            'kehadiran' => 'required|array',
            'kehadiran.*' => 'required|string',
        ]);

        foreach ($request->kehadiran as $mhswId => $jenisPresensiId) {
            $krs = DB::table('krs')
                ->where('JadwalID', $presensi->JadwalID)
                ->where('TahunID', $presensi->TahunID)
                ->where('MhswID', $mhswId)
                ->first();

            if ($krs) {
                DB::table('presensimhsw')->updateOrInsert(
                    [
                        'PresensiID' => $presensi->PresensiID,
                        'MhswID' => $mhswId,
                    ],
                    [
                        'JadwalID' => $presensi->JadwalID,
                        'KRSID' => $krs->KRSID,
                        'JenisPresensiID' => $jenisPresensiId,
                    ]
                );
            }
        }

        return back()->with('status', 'Absensi mahasiswa berhasil diperbarui!');
    }
}

