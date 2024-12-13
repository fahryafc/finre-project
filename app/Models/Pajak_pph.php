<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak_pph extends Model
{
    use HasFactory;
    protected $table = 'Pajak_pph';
    protected $primaryKey = 'id_pajak';

    public $incrementing = false;

    protected $fillable = [
        'id_pengeluaran',
        'nm_karyawan',
        'gaji_karyawan',
        'pph_terutang',
        'potongan',
        'bersih_diterima'
    ];
}
