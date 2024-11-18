<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak_ppn extends Model
{
    use HasFactory;
    protected $table = 'Pajak_ppn';
    protected $primaryKey = 'id_pajak_ppn';

    public $incrementing = false;

    protected $fillable = [        
        'jenis_transaksi',
        'keterangan',
        'nilai_transaksi',
        'persen_pajak',
        'jenis_pajak',
        'saldo_pajak'
    ];
}
