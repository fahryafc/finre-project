<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arusuang extends Model
{
    use HasFactory;
    protected $table = 'arus_uang';
    protected $primaryKey = 'id_uang';

    public $incrementing = false;

    protected $fillable = [
        'kode_akun',
        'nominal',
        'type',
        'tanggal'
    ];
}
