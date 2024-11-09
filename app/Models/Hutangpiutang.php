<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hutangpiutang extends Model
{
    use HasFactory;
    protected $table = 'hutangpiutang';
    protected $primaryKey = 'id_hutangpiutang';

    public $incrementing = false;

    protected $fillable = [
        'id_kontak',
        'kategori',
        'jenis',
        'nominal',
        'status',
        'tgl_jatuh_tempo'
    ];

    public function kontak()
    {
        return $this->hasOne(Kontak::class, 'id_kontak', 'id_kontak');
    }
}
