<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'alamat',
        'reset_password_token',
        'reset_password_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'reset_password_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'reset_password_token_expires_at' => 'datetime',
        ];
    }

    public function anggota()
    {
        return $this->hasOne(\App\Models\Anggota::class, 'user_id', 'id');
    }

    public function isVerified()
    {
        return $this->status === 'active';
    }

    public function isPendingVerification()
    {
        return $this->status === 'verify';
    }

    public function isBanned()
    {
        return $this->status === 'banned';
    }

    // Method untuk generate reset token
    public function generateResetToken()
    {
        $this->reset_password_token = rand(100000, 999999); // 6 digit
        $this->reset_password_token_expires_at = now()->addMinutes(30);
        $this->save();

        return $this->reset_password_token;
    }

    // Method untuk clear reset token
    public function clearResetToken()
    {
        $this->reset_password_token = null;
        $this->reset_password_token_expires_at = null;
        $this->save();
    }

    // Method untuk validasi token
    public function isValidResetToken($token)
    {
        return $this->reset_password_token === $token &&
               $this->reset_password_token_expires_at &&
               $this->reset_password_token_expires_at->isFuture();
    }
}
