<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManajemenPasien extends Model
{
    protected $table = 'manajemen_pasien';

    protected $fillable = [
        'user_id',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
    ];
}
