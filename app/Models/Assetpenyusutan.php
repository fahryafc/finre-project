<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assetpenyusutan extends Model
{
    use HasFactory;

    protected $table = 'asset_penyusutan';
    protected $primaryKey = 'id_penyusutan';

    protected $fillable = [
        'id_aset',
        'masa_manfaat',
        'nilai_tahun',
        'nominal_masa_manfaat',
        'nominal_nilai_tahun',
        'akun_penyusutan',
        'akumulasi_akun',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'id_aset', 'id_aset');
    }
}
