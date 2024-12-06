<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';

    public $incrementing = false;

    protected $fillable = [
        'id_kontak',
        'tanggal',
        'produk',
        'kategori_produk',
        'satuan',
        'harga',
        'kuantitas',
        'diskon',
        'pajak',
        'kode_reff_pajak',
        'jns_pajak',
        'persen_pajak',
        'nominal_pajak',
        'piutang',
        'ongkir',
        'pembayaran',
        'total_pemasukan',
        'tgl_jatuh_tempo'
    ];

    public function kontak()
    {
        return $this->hasOne(Kontak::class, 'id_kontak', 'id_kontak');
    }
}
