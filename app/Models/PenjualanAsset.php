<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanAsset extends Model
{
    use HasFactory;

    protected $table = 'penjualan_asset';
    protected $primaryKey = 'id_penjualan_asset';

    protected $fillable = [
        'id_aset',
        'nm_pelanggan',
        'nm_perusahaan',
        'no_hp',
        'gender',
        'email',
        'alamat',
        'kuantitas',
        'tgl_penjualan',
        'harga_pelepasan',
        'nilai_penyusutan_terakhir',
        'nilai_buku',
        'akun_deposit',
        'nominal_deposit',
        'akun_keuntungan_kerugian',
        'nominal_keuntungan_kerugian',
        'kategori',
        'jns_pajak',
        'pajak_dibayarkan',
        'pajak',
        'user_id',
    ];

    const CODE_JURNAL = 'PA';

    public function asset()
    {
        return $this->hasOne(Aset::class, 'id_aset', 'id_aset');
    }
}
