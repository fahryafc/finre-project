<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;
    protected $table = 'akun';
    protected $primaryKey = 'id_akun';

    public $incrementing = false;

    protected $fillable = ['id_kategori_akun','nama_akun', 'kode_akun', 'kategori_akun', 'subakun'];

    public function kategoriAkun()
    {
        return $this->belongsTo(Kategori_akun::class, 'kategori_akun', 'id_kategori_akun');
    }

    public function subakun()
    {
        return $this->belongsTo(Subakun::class, 'subakun', 'id_subakun');
    }
}
