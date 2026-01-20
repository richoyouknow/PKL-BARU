<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'jenis_transaksi',
        'anggota_id',
        'simpanan_id',
        'pinjaman_id',
        'jumlah',
        'saldo_sebelum',
        'saldo_sesudah',
        'keterangan',
        'status',
        'admin_id',
        'diverifikasi_pada',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
        'diverifikasi_pada' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Constants
    const JENIS_SIMPANAN = 'simpanan';
    const JENIS_PINJAMAN = 'pinjaman';
    const JENIS_PENARIKAN_SIMPANAN = 'penarikan_simpanan';
    const JENIS_PEMBAYARAN_PINJAMAN = 'pembayaran_pinjaman';

    const STATUS_PENDING = 'pending';
    const STATUS_SUKSES = 'sukses';
    const STATUS_GAGAL = 'gagal';
    const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    // Relationships
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function simpanan(): BelongsTo
    {
        return $this->belongsTo(Simpanan::class);
    }

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Accessors
    public function getJenisTransaksiLabelAttribute(): string
    {
        return match($this->jenis_transaksi) {
            self::JENIS_SIMPANAN => 'Setoran Simpanan',
            self::JENIS_PINJAMAN => 'Pencairan Pinjaman',
            self::JENIS_PENARIKAN_SIMPANAN => 'Penarikan Simpanan',
            self::JENIS_PEMBAYARAN_PINJAMAN => 'Pembayaran Angsuran',
            default => $this->jenis_transaksi,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SUKSES => 'Sukses',
            self::STATUS_GAGAL => 'Gagal',
            self::STATUS_MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
            default => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_SUKSES => 'success',
            self::STATUS_GAGAL => 'danger',
            self::STATUS_MENUNGGU_VERIFIKASI => 'info',
            default => 'secondary',
        };
    }

    public function getJumlahFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 2, ',', '.');
    }

    public function getTipeMutasiAttribute(): string
    {
        // Untuk menentukan apakah transaksi ini debit atau kredit
        return in_array($this->jenis_transaksi, [self::JENIS_SIMPANAN, self::JENIS_PEMBAYARAN_PINJAMAN])
            ? 'Kredit'
            : 'Debit';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeSukses($query)
    {
        return $query->where('status', self::STATUS_SUKSES);
    }

    public function scopeGagal($query)
    {
        return $query->where('status', self::STATUS_GAGAL);
    }

    public function scopeMenungguVerifikasi($query)
    {
        return $query->where('status', self::STATUS_MENUNGGU_VERIFIKASI);
    }

    public function scopeSimpanan($query)
    {
        return $query->whereIn('jenis_transaksi', [self::JENIS_SIMPANAN, self::JENIS_PENARIKAN_SIMPANAN]);
    }

    public function scopePinjaman($query)
    {
        return $query->whereIn('jenis_transaksi', [self::JENIS_PINJAMAN, self::JENIS_PEMBAYARAN_PINJAMAN]);
    }

    public function scopeByAnggota($query, $anggotaId)
    {
        return $query->where('anggota_id', $anggotaId);
    }

    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_transaksi', $jenis);
    }

    // Helper methods
    public function isSimpanan(): bool
    {
        return in_array($this->jenis_transaksi, [self::JENIS_SIMPANAN, self::JENIS_PENARIKAN_SIMPANAN]);
    }

    public function isPinjaman(): bool
    {
        return in_array($this->jenis_transaksi, [self::JENIS_PINJAMAN, self::JENIS_PEMBAYARAN_PINJAMAN]);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSukses(): bool
    {
        return $this->status === self::STATUS_SUKSES;
    }

    public function isGagal(): bool
    {
        return $this->status === self::STATUS_GAGAL;
    }

    public function isMenungguVerifikasi(): bool
    {
        return $this->status === self::STATUS_MENUNGGU_VERIFIKASI;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            if (empty($transaksi->kode_transaksi)) {
                $transaksi->kode_transaksi = static::generateKodeTransaksi();
            }
        });
    }

    // Generate kode transaksi otomatis
    public static function generateKodeTransaksi(): string
    {
        $prefix = 'TRX';
        $date = now()->format('Ymd');

        $lastTransaksi = static::whereDate('created_at', now())
            ->latest('id')
            ->first();

        $number = $lastTransaksi ? (int) substr($lastTransaksi->kode_transaksi, -4) + 1 : 1;

        return sprintf('%s-%s-%04d', $prefix, $date, $number);
    }

    // Get jenis transaksi options
    public static function getJenisOptions(): array
    {
        return [
            self::JENIS_SIMPANAN => 'Setoran Simpanan',
            self::JENIS_PINJAMAN => 'Pencairan Pinjaman',
            self::JENIS_PENARIKAN_SIMPANAN => 'Penarikan Simpanan',
            self::JENIS_PEMBAYARAN_PINJAMAN => 'Pembayaran Angsuran',
        ];
    }

    // Get status options
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SUKSES => 'Sukses',
            self::STATUS_GAGAL => 'Gagal',
            self::STATUS_MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
        ];
    }
}
