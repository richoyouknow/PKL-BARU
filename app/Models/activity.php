<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'project_count',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'project_count' => 'integer',
    ];

    // Scope untuk mengambil data yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk mengurutkan data
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Event untuk auto generate slug dari title
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($activity) {
            if (empty($activity->slug)) {
                $activity->slug = Str::slug($activity->title);
            }
        });

        static::updating(function ($activity) {
            if (empty($activity->slug)) {
                $activity->slug = Str::slug($activity->title);
            }
        });
    }
}
