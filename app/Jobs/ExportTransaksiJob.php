<?php

namespace App\Jobs;

use App\Models\Transaksi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportTransaksiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filters;
    public $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(array $filters, int $userId)
    {
        $this->filters = $filters;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Query dengan filters
        $query = Transaksi::with(['anggota', 'simpanan', 'pinjaman', 'admin']);

        if (!empty($this->filters['jenis_transaksi'])) {
            $query->whereIn('jenis_transaksi', (array) $this->filters['jenis_transaksi']);
        }

        if (!empty($this->filters['status'])) {
            $query->whereIn('status', (array) $this->filters['status']);
        }

        if (!empty($this->filters['created_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['created_from']);
        }

        if (!empty($this->filters['created_until'])) {
            $query->whereDate('created_at', '<=', $this->filters['created_until']);
        }

        // Tentukan nama file
        $filename = 'exports/transaksi_export_' . now()->format('Ymd_His') . '_' . $this->userId . '.csv';

        // Pastikan folder exports ada di disk public
        Storage::disk('public')->makeDirectory('exports');

        $filePath = Storage::disk('public')->path($filename);
        $file = fopen($filePath, 'w');

        // Add BOM untuk support Microsoft Excel
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header CSV
        fputcsv($file, [
            'Kode Transaksi',
            'Tanggal',
            'Jenis Transaksi',
            'Nama Anggota',
            'No. Ref (Simpanan/Pinjaman)',
            'Mutasi',
            'Jumlah (Rp)',
            'Status',
            'Keterangan'
        ]);

        // Query secara lazy untuk menghemat memory (O(1) memory)
        $query->orderBy('created_at', 'desc')->lazy()->each(function ($transaksi) use ($file) {
            $noReferensi = '-';
            if ($transaksi->simpanan_id && $transaksi->simpanan) {
                $noReferensi = $transaksi->simpanan->no_simpanan;
            } elseif ($transaksi->pinjaman_id && $transaksi->pinjaman) {
                $noReferensi = $transaksi->pinjaman->no_pinjaman;
            }

            fputcsv($file, [
                $transaksi->kode_transaksi,
                $transaksi->created_at->format('d-m-Y H:i'),
                $transaksi->jenis_transaksi_label ?? $transaksi->jenis_transaksi,
                $transaksi->anggota ? $transaksi->anggota->nama : '-',
                $noReferensi,
                $transaksi->tipe_mutasi,
                number_format($transaksi->jumlah, 0, ',', '.'),
                $transaksi->status_label ?? $transaksi->status,
                $transaksi->keterangan ?? '-',
            ]);
        });

        fclose($file);
    }
}
