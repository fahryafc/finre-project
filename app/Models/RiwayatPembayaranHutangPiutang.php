<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPembayaranHutangPiutang extends Model
{
    use HasFactory;
    protected $table = 'riwayat_pembayaran_hutangpiutang';
    protected $primaryKey = 'id_pembayaran_hutangpiutang';

    public $incrementing = false;

    protected $fillable = [
        'id_hutangpiutang',
        'jenis_riwayat',
        'tanggal_pembayaran',
        'dibayarkan',
        'sisa_pembayaran',
        'masuk_akun',
        'catatan'
    ];

    public function hutangpiutang()
    {
        return $this->hasOne(Kontak::class, 'id_hutangpiutang', 'id_hutangpiutang');
    }
}
