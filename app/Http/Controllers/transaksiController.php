<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data anggota yang sedang login
        $anggota = Auth::user()->anggota;

        if (!$anggota) {
            return redirect()->route('dashboard')->with('error', 'Data anggota tidak ditemukan');
        }

        // Query transaksi milik anggota yang login
        $query = Transaksi::with(['simpanan', 'pinjaman', 'admin'])
            ->where('anggota_id', $anggota->id);

        // Filter berdasarkan jenis transaksi
        if ($request->filled('jenis_transaksi') && $request->jenis_transaksi !== 'semua') {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // Urutkan berdasarkan tanggal terbaru dan paginate
        $transaksis = $query->orderBy('created_at', 'desc')->paginate(10);

        // Hitung statistik untuk anggota
        $stats = [
            'total_transaksi' => Transaksi::where('anggota_id', $anggota->id)->count(),
            'total_sukses' => Transaksi::where('anggota_id', $anggota->id)
                ->where('status', 'sukses')
                ->count(),
            'total_pending' => Transaksi::where('anggota_id', $anggota->id)
                ->where('status', 'pending')
                ->count(),
            'total_simpanan' => Transaksi::where('anggota_id', $anggota->id)
                ->where('jenis_transaksi', 'simpanan')
                ->where('status', 'sukses')
                ->sum('jumlah'),
            'total_pinjaman' => Transaksi::where('anggota_id', $anggota->id)
                ->where('jenis_transaksi', 'pinjaman')
                ->where('status', 'sukses')
                ->sum('jumlah'),
        ];

        // Kirim data ke view - PENTING: pastikan $stats ada di sini
        return view('anggota.transaksi', compact('transaksis', 'stats'));
    }

    public function show($id)
    {
        $anggota = Auth::user()->anggota;

        if (!$anggota) {
            return redirect()->route('dashboard')->with('error', 'Data anggota tidak ditemukan');
        }

        // Pastikan transaksi milik anggota yang login
        $transaksi = Transaksi::with(['simpanan', 'pinjaman', 'admin', 'anggota'])
            ->where('anggota_id', $anggota->id)
            ->findOrFail($id);

        return view('anggota.transaksi.show', compact('transaksi'));
    }

    public function export(Request $request)
    {
        $anggota = Auth::user()->anggota;

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan');
        }

        // Query transaksi dengan filter yang sama
        $query = Transaksi::where('anggota_id', $anggota->id);

        if ($request->filled('jenis_transaksi') && $request->jenis_transaksi !== 'semua') {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();

        // Buat CSV
        $filename = 'transaksi_' . str_replace(' ', '_', $anggota->nama) . '_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($transaksis) {
            $file = fopen('php://output', 'w');

            // Add BOM untuk support Excel Indonesia
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header CSV
            fputcsv($file, [
                'Kode Transaksi',
                'Tanggal',
                'Jenis Transaksi',
                'Jumlah (Rp)',
                'Status',
                'Keterangan'
            ]);

            // Data
            foreach ($transaksis as $transaksi) {
                fputcsv($file, [
                    $transaksi->kode_transaksi,
                    $transaksi->created_at->format('d-m-Y H:i'),
                    $transaksi->jenis_transaksi_label,
                    number_format($transaksi->jumlah, 0, ',', '.'),
                    $transaksi->status_label,
                    $transaksi->keterangan ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
