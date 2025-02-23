<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;
    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';

    public $incrementing = true;

    protected $fillable = [
        'nm_pengeluaran',
        'jenis_pengeluaran',
        'id_kontak',
        'tanggal',
        'kategori',
        'biaya',
        'pajak',
        'kode_reff_pajak',
        'jns_pajak',
        'pajak_persen',
        'pajak_dibayarkan',
        'hutang',
        'nominal_hutang',
        'akun_pembayaran',
        'akun_pemasukan',
        'tgl_jatuh_tempo',
        'user_id'
    ];

    const CODE_JURNAL = 'BL';

    public function kontak()
    {
        return $this->hasOne(Kontak::class, 'id_kontak', 'id_kontak');
    }
}
