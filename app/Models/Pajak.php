<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak extends Model
{
    use HasFactory;
    protected $table = 'Pajak';
    protected $primaryKey = 'id_pajak';

    public $incrementing = false;

    protected $fillable = [
        'nama_produk',
        'gol_pajak',
        'total_pajak',
        'persen_pajak',
        'nominal_pajak'
    ];
}
