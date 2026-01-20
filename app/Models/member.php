<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'photo',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    // Method untuk mengecek apakah ada social media
    public function hasSocialMedia()
    {
        return $this->facebook_url ||
               $this->twitter_url ||
               $this->instagram_url ||
               $this->linkedin_url;
    }
}
