<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManajemenPasien extends Model
{
    protected $table = 'pasien';

    protected $primaryKey = 'id_pasien';

    protected $fillable = [
        'id_pengguna',
        'no_hp',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }
}
