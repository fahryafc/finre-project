<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modal extends Model
{
    use HasFactory;
    protected $table = 'modal';
    protected $primaryKey = 'id_modal';

    public $incrementing = false;

    protected $fillable = ['tanggal', 'jns_transaksi', 'nama_badan', 'nominal', 'masuk_akun', 'credit_akun', 'keterangan'];
}
