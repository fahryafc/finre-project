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
        'id_kontak',
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
        'jns_pajak',
        'persen_pajak',	
        'nominal_pajak',
        'total_transaksi'
    ];
}
