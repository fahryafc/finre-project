<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak_ppnbm extends Model
{
    use HasFactory;
    protected $table = 'Pajak_ppnbm';
    protected $primaryKey = 'id_ppnbm';

    public $incrementing = false;

    protected $fillable = [
        'deskripsi_barang',
        'harga_barang',
        'tarif_ppnbm',
        'ppnbm_dikenakan',
        'tgl_transaksi',
        'keterangan'
    ];
}
