<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anggota extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'anggotas';

    protected $fillable = [
        'user_id',
        'no_registrasi',
        'no_anggota',
        'nama',
        'alamat',
        'no_telepon',
        'grup_wilayah',
        'jenis_identitas',
        'no_identitas',
        'berlaku_sampai',
        'agama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nama_pasangan',
        'pekerjaan',
        'pendapatan',
        'alamat_kantor',
        'keterangan',
        'foto',
        'tanggal_daftar',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'berlaku_sampai' => 'date',
        'tanggal_daftar' => 'date',
        'pendapatan' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($anggota) {
            // Generate no_registrasi otomatis
            if (empty($anggota->no_registrasi)) {
                $lastAnggota = self::withTrashed()
                    ->where('no_registrasi', 'like', 'REG-%')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastAnggota && preg_match('/REG-(\d+)/', $lastAnggota->no_registrasi, $matches)) {
                    $nextNumber = str_pad((int)$matches[1] + 1, 6, '0', STR_PAD_LEFT);
                } else {
                    $nextNumber = '000001';
                }

                $anggota->no_registrasi = 'REG-' . $nextNumber;
            }

            // Set tanggal_daftar otomatis ke tanggal sekarang
            if (empty($anggota->tanggal_daftar)) {
                $anggota->tanggal_daftar = now();
            }

            // Generate no_anggota otomatis untuk semua grup wilayah
            if (empty($anggota->no_anggota)) {
                $anggota->no_anggota = self::generateNoAnggota();
            }
        });

        static::updating(function ($anggota) {
            // Generate no_anggota jika belum ada (untuk data lama yang di-update)
            if (empty($anggota->no_anggota)) {
                $anggota->no_anggota = self::generateNoAnggota();
            }
        });
    }

    /**
     * Generate nomor anggota otomatis
     * Format: AGT-YYYY-NNNNNN
     */
    public static function generateNoAnggota()
    {
        $prefix = 'AGT';
        $year = date('Y');

        $lastAnggota = self::withTrashed()
            ->where('no_anggota', 'like', "{$prefix}-{$year}-%")
            ->orderBy('no_anggota', 'desc')
            ->first();

        if ($lastAnggota && preg_match('/' . $prefix . '-' . $year . '-(\d+)/', $lastAnggota->no_anggota, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s-%s-%06d', $prefix, $year, $nextNumber);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto && Storage::disk('public')->exists('images/' . $this->foto)) {
            return asset('storage/images/' . $this->foto);
        }

        return asset('images/default-avatar.png');
    }
}
