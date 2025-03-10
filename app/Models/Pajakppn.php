<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajakppn extends Model
{
    use HasFactory;
    protected $table = 'pajak_ppn';
    protected $primaryKey = 'id_pajak_ppn';

    public $incrementing = false;

    protected $fillable = [        
        'kode_reff',
        'jenis_transaksi',
        'keterangan',
        'nilai_transaksi',
        'persen_pajak',
        'jenis_pajak',
        'saldo_pajak'
    ];
}
