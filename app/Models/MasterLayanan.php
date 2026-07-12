<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterLayanan extends Model
{
    use HasFactory;

    // Memberitahu Laravel bahwa model ini memakai tabel 'master_layanan'
    protected $table = 'master_layanan';

    // Menentukan primary key yang sesuai dengan ERD kita
    protected $primaryKey = 'id_layanan';

    // Mendaftarkan kolom yang boleh diisi massal saat input CRUD nanti
    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',
        'diskon',
    ];
}
