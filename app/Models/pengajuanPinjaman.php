<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPinjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'anggota_id',
        'jumlah_pinjaman',
        'tenor',
        'tujuan_pinjaman',
        'status',
        'admin_id',
        'catatan_admin',
        'disetujui_pada'
    ];

    protected $casts = [
        'jumlah_pinjaman' => 'decimal:2',
        'disetujui_pada' => 'datetime',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function pinjaman()
    {
        return $this->hasOne(Pinjaman::class);
    }

    public function getStatusTextAttribute()
    {
        $status = [
            'menunggu' => 'Menunggu Verifikasi',
            'diproses' => 'Sedang Diproses',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ];

        return $status[$this->status] ?? $this->status;
    }
}
