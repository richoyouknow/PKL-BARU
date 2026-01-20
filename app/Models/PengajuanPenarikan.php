<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanPenarikan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_penarikans';

    protected $fillable = [
        'anggota_id',
        'simpanan_id',
        'jumlah',
        'alasan',
        'status',
        'admin_id',
        'catatan_admin',
        'disetujui_pada'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'disetujui_pada' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Konstanta status
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';

    /**
     * Relationship dengan anggota
     */
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    /**
     * Relationship dengan simpanan
     */
    public function simpanan(): BelongsTo
    {
        return $this->belongsTo(Simpanan::class, 'simpanan_id');
    }

    /**
     * Relationship dengan admin (user yang memverifikasi)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get status text untuk display
     */
    public function getStatusTextAttribute(): string
    {
        $status = [
            self::STATUS_MENUNGGU => 'Menunggu Verifikasi',
            self::STATUS_DIPROSES => 'Sedang Diproses',
            self::STATUS_DISETUJUI => 'Disetujui',
            self::STATUS_DITOLAK => 'Ditolak',
        ];

        return $status[$this->status] ?? $this->status;
    }

    /**
     * Get status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_MENUNGGU => 'Menunggu Verifikasi',
            self::STATUS_DIPROSES => 'Sedang Diproses',
            self::STATUS_DISETUJUI => 'Disetujui',
            self::STATUS_DITOLAK => 'Ditolak',
        ];
    }

    /**
     * Scope untuk pengajuan yang menunggu
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', self::STATUS_MENUNGGU);
    }

    /**
     * Scope untuk pengajuan yang disetujui
     */
    public function scopeDisetujui($query)
    {
        return $query->where('status', self::STATUS_DISETUJUI);
    }

    /**
     * Scope untuk pengajuan yang ditolak
     */
    public function scopeDitolak($query)
    {
        return $query->where('status', self::STATUS_DITOLAK);
    }

    /**
     * Check if status is menunggu
     */
    public function isMenunggu(): bool
    {
        return $this->status === self::STATUS_MENUNGGU;
    }

    /**
     * Check if status is disetujui
     */
    public function isDisetujui(): bool
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    /**
     * Check if status is ditolak
     */
    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    /**
     * Format jumlah untuk display
     */
    public function getJumlahFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Set default status saat creating
        static::creating(function ($model) {
            if (empty($model->status)) {
                $model->status = self::STATUS_MENUNGGU;
            }
        });
    }
}
