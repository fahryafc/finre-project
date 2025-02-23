<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';

    protected $fillable = [
        'id_kontak',
        'tanggal',
        'piutang',
        'ongkir',
        'pembayaran',
        'total_pajak',
        'total_diskon',
        'total_pemasukan',
        'tgl_jatuh_tempo',
        'created_at',
        'updated_at',
        'user_id'
    ];

    const CODE_JURNAL = 'JL';

    public function kontak()
    {
        return $this->hasOne(Kontak::class, 'id_kontak', 'id_kontak');
    }

    public function produkPenjualan()
    {
        return $this->hasMany(ProdukPenjualan::class, 'id_penjualan', 'id_penjualan');
    }
}
