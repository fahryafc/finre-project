<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'Produk';
    protected $primaryKey = 'id_produk';

    public $incrementing = false;

    protected $fillable = [
        'pemasok',
        'no_hp',
        'nm_perusahaan',
        'email',
        'alamat',
        'nama_produk',
        'satuan',
        'kategori',
        'kuantitas',
        'kode_sku',
        'tanggal',
        'harga_beli',
        'harga_jual',
        'akun_pembayaran',
        'masuk_akun',
    ];
}
