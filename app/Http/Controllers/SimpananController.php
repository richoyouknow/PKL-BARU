<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Models\Transaksi;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpananController extends Controller
{
    /**
     * Display dashboard simpanan anggota
     */
    public function index()
    {
        // Ambil data anggota yang login
        // Sesuaikan dengan sistem auth Anda
        $anggota = Auth::user()->anggota ?? Anggota::first(); // Fallback untuk testing

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan');
        }

        // Ambil semua simpanan aktif anggota
        $simpananList = Simpanan::where('anggota_id', $anggota->id)
            ->where('status', Simpanan::STATUS_AKTIF)
            ->get();

        // Hitung total simpanan
        $totalSimpanan = $simpananList->sum('saldo');

        // Hitung jumlah simpanan per jenis
        $jumlahSimpananPerJenis = $simpananList->groupBy('jenis_simpanan')
            ->map(function ($items) {
                return $items->count();
            });

        // Format untuk display
        $simpananAktifDetail = [];
        foreach ($jumlahSimpananPerJenis as $jenis => $jumlah) {
            $jenisLabel = match($jenis) {
                'simpanan_pokok' => 'Pokok',
                'simpanan_wajib' => 'Wajib',
                'simpanan_sukarela' => 'Sukarela',
                'simpanan_berjangka' => 'Berjangka',
                default => $jenis
            };
            $simpananAktifDetail[] = "$jumlah $jenisLabel";
        }

        // Ambil riwayat transaksi simpanan anggota (10 terakhir)
        $transaksiList = Transaksi::where('anggota_id', $anggota->id)
            ->whereIn('jenis_transaksi', ['simpanan', 'penarikan_simpanan'])
            ->where('status', 'sukses')
            ->with(['simpanan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Ambil simpanan terbesar untuk menentukan plafon (opsional)
        $plafonTotal = $simpananList->max('saldo') * 1.5; // Contoh: 150% dari simpanan terbesar
        if ($plafonTotal == 0) {
            $plafonTotal = 20000000; // Default plafon
        }

        // Cek status kolektibilitas (berdasarkan transaksi)
        $statusKolektibilitas = $this->getStatusKolektibilitas($anggota->id);

        return view('anggota.simpanan', compact(
            'anggota',
            'simpananList',
            'totalSimpanan',
            'plafonTotal',
            'simpananAktifDetail',
            'transaksiList',
            'statusKolektibilitas'
        ));
    }

    /**
     * Get status kolektibilitas anggota
     */
    private function getStatusKolektibilitas($anggotaId)
    {
        // Cek apakah ada transaksi gagal atau pending dalam 30 hari terakhir
        $transaksiGagal = Transaksi::where('anggota_id', $anggotaId)
            ->whereIn('status', ['gagal', 'pending'])
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        if ($transaksiGagal > 0) {
            return [
                'status' => 'Perlu Perhatian',
                'keterangan' => "Ada $transaksiGagal transaksi yang perlu ditindaklanjuti",
                'color' => 'orange'
            ];
        }

        // Cek pinjaman yang menunggak (jika ada)
        // Anda bisa tambahkan logic lebih kompleks di sini

        return [
            'status' => 'Lancar',
            'keterangan' => 'Tidak ada tunggakan',
            'color' => 'green'
        ];
    }

    /**
     * Form untuk ajukan penarikan simpanan
     */
    public function formPenarikan()
    {
        $anggota = Auth::user()->anggota ?? Anggota::first();

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan');
        }

        // Ambil simpanan aktif yang bisa ditarik
        $simpananList = Simpanan::where('anggota_id', $anggota->id)
            ->where('status', Simpanan::STATUS_AKTIF)
            ->where('saldo', '>', 0)
            ->get();

        return view('anggota.penarikansimpanan', compact('anggota', 'simpananList'));
    }

    /**
     * Proses ajuan penarikan simpanan
     */
    public function submitPenarikan(Request $request)
    {
        $request->validate([
            'simpanan_id' => 'required|exists:simpanans,id',
            'jumlah' => 'required|numeric|min:10000',
            'keterangan' => 'required|string|max:500',
        ]);

        $anggota = Auth::user()->anggota ?? Anggota::first();

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan');
        }

        // Validasi simpanan milik anggota
        $simpanan = Simpanan::where('id', $request->simpanan_id)
            ->where('anggota_id', $anggota->id)
            ->first();

        if (!$simpanan) {
            return redirect()->back()->with('error', 'Simpanan tidak ditemukan');
        }

        // Validasi saldo mencukupi
        if ($simpanan->saldo < $request->jumlah) {
            return redirect()->back()->with('error', 'Saldo simpanan tidak mencukupi');
        }

        // Buat transaksi penarikan dengan status menunggu verifikasi
        $transaksi = Transaksi::create([
            'kode_transaksi' => '', // Auto-generate di boot method
            'jenis_transaksi' => 'penarikan_simpanan',
            'anggota_id' => $anggota->id,
            'simpanan_id' => $simpanan->id,
            'jumlah' => $request->jumlah,
            'saldo_sebelum' => $simpanan->saldo,
            'saldo_sesudah' => $simpanan->saldo - $request->jumlah,
            'keterangan' => $request->keterangan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('simpanan.dashboard')
            ->with('success', "Pengajuan penarikan berhasil dibuat dengan kode {$transaksi->kode_transaksi}. Menunggu verifikasi admin.");
    }

    /**
     * Detail simpanan tertentu
     */
    public function detail($id)
    {
        $anggota = Auth::user()->anggota ?? Anggota::first();

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan');
        }

        $simpanan = Simpanan::where('id', $id)
            ->where('anggota_id', $anggota->id)
            ->firstOrFail();

        // Ambil transaksi untuk simpanan ini
        $transaksiList = Transaksi::where('simpanan_id', $simpanan->id)
            ->where('status', 'sukses')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('simpanan.detail', compact('anggota', 'simpanan', 'transaksiList'));
    }
}
