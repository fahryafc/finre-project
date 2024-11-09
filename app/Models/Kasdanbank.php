<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasdanbank extends Model
{
    use HasFactory;
    protected $table = 'kas_bank';
    protected $primaryKey = 'id_kas_bank';

    public $incrementing = false;

    protected $fillable = ['nama_akun', 'kode_akun', 'kategori_akun', 'subakun'];

    public function kategoriAkun()
    {
        return $this->belongsTo(Kategori_akun::class, 'kategori_akun', 'id_kategori_akun');
    }

    public function subakun()
    {
        return $this->belongsTo(Subakun::class, 'subakun', 'id_subakun');
    }
}
