<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    use HasFactory;

    protected $table = 'kontak';
    protected $primaryKey = 'id_kontak';

    protected $fillable = [
        'jenis_kontak',
        'nama_kontak',
        'email',
        'no_hp',
        'nm_perusahaan',
        'alamat'
    ];
}
