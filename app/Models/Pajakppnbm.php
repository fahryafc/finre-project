<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajakppnbm extends Model
{
    use HasFactory;
    protected $table = 'pajak_ppnbm';
    protected $primaryKey = 'id_ppnbm';

    public $incrementing = false;

    protected $fillable = [
        'kode_reff',
        'deskripsi_barang',
        'harga_barang',
        'tarif_ppnbm',
        'ppnbm_dikenakan',
        'tgl_transaksi',
        'keterangan'
    ];
}
