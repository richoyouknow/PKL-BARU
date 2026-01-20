<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Simpanan extends Model
{
    use HasFactory;

    protected $table = 'simpanans';

    protected $fillable = [
        'anggota_id',
        'no_simpanan',
        'no_rekening',
        'jenis_simpanan',
        'saldo',
        'status'
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Konstanta untuk jenis simpanan
    const JENIS_POKOK = 'simpanan_pokok';
    const JENIS_WAJIB = 'simpanan_wajib';
    const JENIS_SUKARELA = 'simpanan_sukarela';
    const JENIS_BERJANGKA = 'simpanan_berjangka';

    // Konstanta untuk status
    const STATUS_AKTIF = 'aktif';
    const STATUS_NONAKTIF = 'nonaktif';
    const STATUS_DITUTUP = 'ditutup';

    /**
     * Get jenis simpanan options
     */
    public static function getJenisOptions(): array
    {
        return [
            self::JENIS_POKOK => 'Simpanan Pokok',
            self::JENIS_WAJIB => 'Simpanan Wajib',
            self::JENIS_SUKARELA => 'Simpanan Sukarela',
            self::JENIS_BERJANGKA => 'Simpanan Berjangka',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_NONAKTIF => 'Nonaktif',
            self::STATUS_DITUTUP => 'Ditutup',
        ];
    }

    /**
     * Format jenis simpanan untuk display
     */
    public function getJenisSimpananFormattedAttribute(): string
    {
        return self::getJenisOptions()[$this->jenis_simpanan] ?? $this->jenis_simpanan;
    }

    /**
     * Format status untuk display
     */
    public function getStatusFormattedAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Format saldo untuk display
     */
    public function getSaldoFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->saldo, 2, ',', '.');
    }

    /**
     * Relationship dengan anggota
     */
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    /**
     * Relationship dengan transaksi
     */
    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Relationship dengan pengajuan penarikan
     */
    public function pengajuanPenarikan(): HasMany
    {
        return $this->hasMany(PengajuanPenarikan::class);
    }

    /**
     * Scope untuk simpanan aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    /**
     * Scope berdasarkan jenis simpanan
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_simpanan', $jenis);
    }

    /**
     * Cek apakah simpanan aktif
     */
    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    /**
     * Cek apakah simpanan nonaktif
     */
    public function isNonaktif(): bool
    {
        return $this->status === self::STATUS_NONAKTIF;
    }

    /**
     * Cek apakah simpanan ditutup
     */
    public function isDitutup(): bool
    {
        return $this->status === self::STATUS_DITUTUP;
    }

    /**
     * Cek apakah ada pengajuan penarikan yang menunggu
     */
    public function hasPendingWithdrawal(): bool
    {
        return $this->pengajuanPenarikan()
            ->where('status', 'menunggu')
            ->exists();
    }

    /**
     * Get pengajuan penarikan yang sedang menunggu
     */
    public function getPendingWithdrawal()
    {
        return $this->pengajuanPenarikan()
            ->where('status', 'menunggu')
            ->first();
    }

    /**
     * Boot method untuk auto-generate nomor simpanan dan rekening
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($simpanan) {
            if (empty($simpanan->no_simpanan)) {
                $simpanan->no_simpanan = static::generateNoSimpanan($simpanan->jenis_simpanan);
            }

            if (empty($simpanan->no_rekening)) {
                $simpanan->no_rekening = static::generateNoRekening($simpanan->jenis_simpanan);
            }
        });
    }

    /**
     * Generate nomor simpanan otomatis berdasarkan jenis simpanan
     * Format: Simp-[JENIS]-YYYYMMDD-XXXX
     * Contoh: Simp-POK-20251230-0001, Simp-WJB-20251230-0002
     */
    public static function generateNoSimpanan(string $jenis): string
    {
        // Prefix berdasarkan jenis simpanan
        $jenisPrefix = match($jenis) {
            self::JENIS_POKOK => 'POK',
            self::JENIS_WAJIB => 'WJB',
            self::JENIS_SUKARELA => 'SKL',
            self::JENIS_BERJANGKA => 'BJK',
            default => 'UMM'
        };

        $prefix = "Simp-{$jenisPrefix}";

        // Format tanggal: YYYYMMDD
        $date = now()->format('Ymd');

        // Cari nomor simpanan terakhir dengan prefix dan tanggal yang sama
        $last = self::where('no_simpanan', 'like', "{$prefix}-{$date}-%")
            ->orderBy('no_simpanan', 'desc')
            ->first();

        if ($last) {
            // Ambil 4 digit terakhir dan tambah 1
            $lastNumber = (int) substr($last->no_simpanan, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            // Mulai dari 1 jika belum ada nomor untuk tanggal ini
            $nextNumber = 1;
        }

        // Format: Simp-[JENIS]-YYYYMMDD-XXXX
        return sprintf('%s-%s-%04d', $prefix, $date, $nextNumber);
    }

    /**
     * Generate nomor rekening otomatis berdasarkan jenis simpanan
     * Format: [PREFIX]-YYYYMMDD-XXXX
     * Contoh: SP-20251230-0001, SW-20251230-0002
     */
    public static function generateNoRekening(string $jenis): string
    {
        $prefix = match($jenis) {
            self::JENIS_POKOK => 'SP',
            self::JENIS_WAJIB => 'SW',
            self::JENIS_SUKARELA => 'SS',
            self::JENIS_BERJANGKA => 'SB',
            default => 'S'
        };

        $date = now()->format('Ymd');

        // Cari nomor rekening terakhir dengan prefix dan tanggal yang sama
        $last = self::where('no_rekening', 'like', "{$prefix}-{$date}-%")
            ->orderBy('no_rekening', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->no_rekening, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $nextNumber);
    }
}
