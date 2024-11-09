<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori_akun extends Model
{
    use HasFactory;
    protected $table = 'kategori_akun';
    protected $primaryKey = 'id_kategori_akun';

    public $incrementing = false;

    protected $fillable = ['nama_kategori'];

    public function subAkun()
    {
        return $this->hasMany(SubAkun::class, 'id_kategori_akun');
    }
}
