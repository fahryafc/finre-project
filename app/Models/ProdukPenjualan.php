<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukPenjualan extends Model
{
    use HasFactory;
    protected $table = 'produk_penjualan';
    protected $primaryKey = 'id_produk_penjualan';

    public $incrementing = false;

    protected $fillable = [
        'id_penjualan',
        'produk',
        'kategori_produk',
        'satuan',
        'harga',
        'kuantitas',
        'kode_reff_pajak',
        'jns_pajak',
        'persen_pajak',
        'nominal_pajak',
        'persen_diskon',
        'nominal_diskon',
    ];

    public function kontak()
    {
        return $this->hasOne(Kontak::class, 'id_kontak', 'id_kontak');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }
}
