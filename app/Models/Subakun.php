<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subakun extends Model
{
    use HasFactory;
    protected $table = 'subakun_kategori';
    protected $primaryKey = 'id_subakun';

    public $incrementing = false;

    protected $fillable = [
        'id_kategori_akun',
        'kode',
        'nama_subakun',
    ];

    public function kategoriAkun()
    {
        return $this->belongsTo(Kategori_akun::class, 'id_kategori_akun');
    }
}
