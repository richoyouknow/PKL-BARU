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

            // Set no_anggota otomatis jika status Anggota
            if ($anggota->grup_wilayah === 'Anggotas' && empty($anggota->no_anggota)) {
                $lastAnggotaNumber = self::withTrashed()
                    ->where('grup_wilayah', 'Anggota')
                    ->whereNotNull('no_anggota')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastAnggotaNumber && preg_match('/^(\d+)$/', $lastAnggotaNumber->no_anggota, $matches)) {
                    $nextAnggotaNumber = (int)$matches[1] + 1;
                } else {
                    $nextAnggotaNumber = 1;
                }

                $anggota->no_anggota = str_pad($nextAnggotaNumber, 6, '0', STR_PAD_LEFT);
            }
        });

        static::updating(function ($anggota) {
            // Jika status berubah menjadi Anggota dan belum ada no_anggota
            if ($anggota->grup_wilayah === 'Anggota' && empty($anggota->no_anggota)) {
                $lastAnggotaNumber = self::withTrashed()
                    ->where('grup_wilayah', 'Anggota')
                    ->whereNotNull('no_anggota')
                    ->where('id', '!=', $anggota->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastAnggotaNumber && preg_match('/^(\d+)$/', $lastAnggotaNumber->no_anggota, $matches)) {
                    $nextAnggotaNumber = (int)$matches[1] + 1;
                } else {
                    $nextAnggotaNumber = 1;
                }

                $anggota->no_anggota = str_pad($nextAnggotaNumber, 6, '0', STR_PAD_LEFT);
            }

            // Jika status berubah dari Anggota ke yang lain, kosongkan no_anggota
            if ($anggota->grup_wilayah !== 'Anggota' && !empty($anggota->no_anggota)) {
                $anggota->no_anggota = null;
            }
        });
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
