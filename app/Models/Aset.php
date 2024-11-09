<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $table = 'asset';
    protected $primaryKey = 'id_aset';

    protected $fillable = [
        'pemasok',
        'no_hp',
        'nm_perusahaan',
        'email',
        'alamat',
        'tanggal',
        'nm_aset',
        'satuan',
        'kuantitas',
        'pajak',
        'jns_pajak',
        'persen_pajak',
        'pajak_dibayarkan',
        'kode_sku',
        'harga_beli',
        'akun_pembayaran',
        'akun_aset',
        'penyusutan',
        'kategori',
    ];

    public function assetPenyusutan()
    {
        return $this->hasOne(AssetPenyusutan::class, 'id_aset', 'id_aset');
    }
}
