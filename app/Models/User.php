<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'id_pengguna';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
        'role',
        'status_aktif'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status_aktif' => 'boolean'
    ];

    public function pasien()
    {
        return $this->hasOne(
            Pasien::class,
            'id_pengguna',
            'id_pengguna'
        );
    }

    public function dokter()
    {
        return $this->hasOne(
            Dokter::class,
            'id_pengguna',
            'id_pengguna'
        );
    }
}

