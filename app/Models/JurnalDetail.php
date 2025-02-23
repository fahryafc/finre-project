<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalDetail extends Model
{
    use HasFactory;
    protected $table = 'jurnal_detail';
    protected $primaryKey = 'id_jurnal_detail';

    // public $incrementing = false;

    protected $fillable = ['id_jurnal', 'debit', 'kredit', 'keterangan'];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class, 'id_jurnal', 'id_jurnal');
    }

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }
}
