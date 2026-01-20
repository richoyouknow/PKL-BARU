<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pinjaman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pinjamen';

    protected $fillable = [
        'anggota_id',
        'no_pinjaman',
        'kategori_pinjaman',
        'jumlah_pinjaman',
        'total_pinjaman_dengan_bunga', // BARU: Total yang harus dibayar (pokok + bunga)
        'saldo_pinjaman',
        'tenor',
        'bunga_per_tahun',
        'angsuran_per_bulan',
        'tanggal_pinjaman',
        'tanggal_jatuh_tempo',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_pinjaman' => 'decimal:2',
        'total_pinjaman_dengan_bunga' => 'decimal:2',
        'saldo_pinjaman' => 'decimal:2',
        'bunga_per_tahun' => 'decimal:2',
        'angsuran_per_bulan' => 'decimal:2',
        'tanggal_pinjaman' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];

    // Relationship
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function getKategoriPinjamanLabelAttribute(): string
    {
        return match ($this->kategori_pinjaman) {
            'pinjaman_cash' => 'Pinjaman Cash',
            'pinjaman_elektronik' => 'Pinjaman Elektronik',
            default => 'Tidak Diketahui',
        };
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'diajukan' => 'warning',
            'diproses' => 'info',
            'disetujui' => 'success',
            'ditolak' => 'danger',
            'aktif' => 'primary',
            'lunas' => 'success',
            'macet' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'diajukan' => 'Diajukan',
            'diproses' => 'Diproses',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'aktif' => 'Aktif',
            'lunas' => 'Lunas',
            'macet' => 'Macet',
            default => $this->status,
        };
    }

    public function getPersentaseLunasAttribute(): float
    {
        if ($this->total_pinjaman_dengan_bunga == 0) {
            return 0;
        }
        return (($this->total_pinjaman_dengan_bunga - $this->saldo_pinjaman) / $this->total_pinjaman_dengan_bunga) * 100;
    }

    public function getJumlahTerbayarAttribute(): float
    {
        return $this->total_pinjaman_dengan_bunga - $this->saldo_pinjaman;
    }

    public function getTotalBungaAttribute(): float
    {
        return $this->total_pinjaman_dengan_bunga - $this->jumlah_pinjaman;
    }

    // Scopes
    public function scopeDiajukan($query)
    {
        return $query->where('status', 'diajukan');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeMacet($query)
    {
        return $query->where('status', 'macet');
    }

    public function scopeByAnggota($query, $anggotaId)
    {
        return $query->where('anggota_id', $anggotaId);
    }

    // Boot method untuk generate no_pinjaman otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pinjaman) {
            if (empty($pinjaman->no_pinjaman)) {
                $pinjaman->no_pinjaman = static::generateNoPinjaman();
            }

            // Set bunga default 1.5% jika tidak diisi
            if (empty($pinjaman->bunga_per_tahun)) {
                $pinjaman->bunga_per_tahun = 1.5;
            }

            // Set status default 'diajukan' jika belum ada
            if (empty($pinjaman->status)) {
                $pinjaman->status = 'diajukan';
            }

            // Hitung angsuran per bulan dan total pinjaman dengan bunga
            if (!empty($pinjaman->tenor) && !empty($pinjaman->jumlah_pinjaman)) {
                $pinjaman->angsuran_per_bulan = static::hitungAngsuran(
                    $pinjaman->jumlah_pinjaman,
                    $pinjaman->tenor,
                    $pinjaman->bunga_per_tahun
                );

                // Total yang harus dibayar = angsuran × tenor
                $pinjaman->total_pinjaman_dengan_bunga = $pinjaman->angsuran_per_bulan * $pinjaman->tenor;
            }

            // Set saldo_pinjaman = total_pinjaman_dengan_bunga saat pertama kali dibuat
            if (empty($pinjaman->saldo_pinjaman)) {
                $pinjaman->saldo_pinjaman = $pinjaman->total_pinjaman_dengan_bunga ?? $pinjaman->jumlah_pinjaman;
            }
        });
    }

    public static function generateNoPinjaman(): string
    {
        $prefix = 'PJM';
        $date = now()->format('Ymd');
        $lastPinjaman = static::whereDate('created_at', now())
            ->latest('id')
            ->first();

        $number = $lastPinjaman ? (int) substr($lastPinjaman->no_pinjaman, -4) + 1 : 1;

        return sprintf('%s-%s-%04d', $prefix, $date, $number);
    }

    // Helper method untuk hitung angsuran
    public static function hitungAngsuran($jumlah, $tenor, $bunga): float
    {
        // Rumus: A = P * (r(1+r)^n) / ((1+r)^n - 1)
        // P = jumlah pinjaman
        // r = bunga per bulan (bunga per tahun / 12 / 100)
        // n = tenor (bulan)

        $r = ($bunga / 12) / 100;

        if ($r == 0) {
            return $jumlah / $tenor;
        }

        $pembilang = $jumlah * $r * pow(1 + $r, $tenor);
        $penyebut = pow(1 + $r, $tenor) - 1;

        return $pembilang / $penyebut;
    }

    // Helper methods untuk status
    public function isDiajukan(): bool
    {
        return $this->status === 'diajukan';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }

    public function isMacet(): bool
    {
        return $this->status === 'macet';
    }

    // Method untuk approve pinjaman
    public function approve(): bool
    {
        if ($this->status === 'diajukan') {
            $this->status = 'disetujui';
            return $this->save();
        }
        return false;
    }

    // Method untuk reject pinjaman
    public function reject($keterangan = null): bool
    {
        if (in_array($this->status, ['diajukan', 'disetujui'])) {
            $this->status = 'ditolak';
            if ($keterangan) {
                $this->keterangan = ($this->keterangan ?? '') . "\nDitolak: " . $keterangan;
            }
            return $this->save();
        }
        return false;
    }

    // Method untuk aktivasi pinjaman
    public function aktivasi(): bool
    {
        if (in_array($this->status, ['diajukan', 'disetujui'])) {
            $this->status = 'aktif';
            $this->tanggal_pinjaman = now();

            if ($this->tenor) {
                $this->tanggal_jatuh_tempo = now()->addMonths($this->tenor);
            }

            return $this->save();
        }
        return false;
    }

    public function getAngsuranTerbayarAttribute(): int
    {
        if ($this->angsuran_per_bulan == 0 || $this->total_pinjaman_dengan_bunga == 0) {
            return 0;
        }

        $totalDibayar = $this->total_pinjaman_dengan_bunga - $this->saldo_pinjaman;
        return (int) floor($totalDibayar / $this->angsuran_per_bulan);
    }

    public function getSisaTenorAttribute(): int
    {
        return max(0, $this->tenor - $this->angsuran_terbayar);
    }

    public function getPersenTenorAttribute(): float
    {
        if ($this->tenor == 0) {
            return 0;
        }
        return min(($this->angsuran_terbayar / $this->tenor) * 100, 100);
    }

    /**
     * Method untuk mencairkan pinjaman dan membuat transaksi
     */
    public function cairkan($adminId = null): bool
    {
        if (!in_array($this->status, ['diajukan', 'disetujui'])) {
            return false;
        }

        try {
            \DB::beginTransaction();

            // Update status pinjaman
            $this->status = 'aktif';
            $this->tanggal_pinjaman = now();

            if ($this->tenor) {
                $this->tanggal_jatuh_tempo = now()->addMonths($this->tenor);
            }

            $this->save();

            // Buat transaksi pencairan (yang dicairkan adalah jumlah pokok)
            Transaksi::create([
                'jenis_transaksi' => Transaksi::JENIS_PINJAMAN,
                'anggota_id' => $this->anggota_id,
                'pinjaman_id' => $this->id,
                'jumlah' => $this->jumlah_pinjaman, // Pencairan = pokok saja
                'saldo_sebelum' => 0,
                'saldo_sesudah' => $this->jumlah_pinjaman,
                'keterangan' => "Pencairan pinjaman {$this->no_pinjaman}",
                'status' => Transaksi::STATUS_SUKSES,
                'admin_id' => $adminId ?? auth()->id(),
                'diverifikasi_pada' => now(),
            ]);

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            return false;
        }
    }

    /**
     * Method untuk pembayaran angsuran
     */
    public function bayarAngsuran($jumlah, $keterangan = null, $adminId = null): bool
    {
        if ($this->status !== 'aktif') {
            return false;
        }

        if ($jumlah <= 0 || $jumlah > $this->saldo_pinjaman) {
            return false;
        }

        try {
            \DB::beginTransaction();

            $saldoSebelum = $this->saldo_pinjaman;
            $this->saldo_pinjaman -= $jumlah;

            // Jika saldo pinjaman habis, ubah status jadi lunas
            if ($this->saldo_pinjaman <= 0) {
                $this->status = 'lunas';
                $this->saldo_pinjaman = 0;
            }

            $this->save();

            // Buat transaksi pembayaran
            Transaksi::create([
                'jenis_transaksi' => Transaksi::JENIS_PEMBAYARAN_PINJAMAN,
                'anggota_id' => $this->anggota_id,
                'pinjaman_id' => $this->id,
                'jumlah' => $jumlah,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $this->saldo_pinjaman,
                'keterangan' => $keterangan ?? "Pembayaran angsuran pinjaman {$this->no_pinjaman}",
                'status' => Transaksi::STATUS_SUKSES,
                'admin_id' => $adminId ?? auth()->id(),
                'diverifikasi_pada' => now(),
            ]);

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            return false;
        }
    }
}
