<?php

namespace App\Observers;

use App\Models\Pinjaman;
use App\Models\Transaksi;

class PinjamanObserver
{
    /**
     * Handle the Pinjaman "created" event.
     */
    public function created(Pinjaman $pinjaman): void
    {
        // Otomatis buat transaksi saat pinjaman baru diajukan
        if ($pinjaman->status === 'diajukan') {
            Transaksi::create([
                'jenis_transaksi' => 'pinjaman',
                'anggota_id' => $pinjaman->anggota_id,
                'pinjaman_id' => $pinjaman->id,
                'jumlah' => $pinjaman->jumlah_pinjaman,
                'saldo_sebelum' => 0,
                'saldo_sesudah' => $pinjaman->jumlah_pinjaman,
                'keterangan' => "Pengajuan pinjaman {$pinjaman->kategori_pinjaman_label} dengan nomor {$pinjaman->no_pinjaman}. Tenor: {$pinjaman->tenor} bulan, Bunga: {$pinjaman->bunga_per_tahun}% per tahun.",
                'status' => 'menunggu_verifikasi',
            ]);
        }
    }

    /**
     * Handle the Pinjaman "updated" event.
     */
    public function updated(Pinjaman $pinjaman): void
    {
        // Jika status pinjaman berubah dari status lain menjadi 'diajukan'
        // dan belum ada transaksi untuk pinjaman ini
        if ($pinjaman->status === 'diajukan' && $pinjaman->wasChanged('status')) {
            $existingTransaksi = Transaksi::where('pinjaman_id', $pinjaman->id)
                ->where('jenis_transaksi', 'pinjaman')
                ->exists();

            if (!$existingTransaksi) {
                Transaksi::create([
                    'jenis_transaksi' => 'pinjaman',
                    'anggota_id' => $pinjaman->anggota_id,
                    'pinjaman_id' => $pinjaman->id,
                    'jumlah' => $pinjaman->jumlah_pinjaman,
                    'saldo_sebelum' => 0,
                    'saldo_sesudah' => $pinjaman->jumlah_pinjaman,
                    'keterangan' => "Pengajuan pinjaman {$pinjaman->kategori_pinjaman_label} dengan nomor {$pinjaman->no_pinjaman}. Tenor: {$pinjaman->tenor} bulan, Bunga: {$pinjaman->bunga_per_tahun}% per tahun.",
                    'status' => 'menunggu_verifikasi',
                ]);
            }
        }
    }
}
