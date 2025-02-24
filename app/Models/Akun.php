<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Akun extends Model
{
    use HasFactory;
    protected $table = 'akun';
    protected $primaryKey = 'id_akun';

    public $incrementing = false;

    protected $fillable = ['id_kategori_akun','type','nama_akun', 'kode_akun', 'kategori_akun', 'subakun'];

    public function kategoriAkun()
    {
        return $this->belongsTo(Kategori_akun::class, 'kategori_akun', 'id_kategori_akun');
    }

    public function subakun()
    {
        return $this->belongsTo(Subakun::class, 'subakun', 'id_subakun');
    }

    public static function getSaldo($from_date, $id_akun)
    {
        if ($from_date == NULL) $from_date = date('Y-m-d');
        return DB::table('jurnal')
            ->select(
                DB::raw('COALESCE(SUM(jurnal_detail.debit-jurnal_detail.kredit),0) AS saldo')
            )
            ->join('jurnal_detail', 'jurnal_detail.id_jurnal', 'jurnal.id_jurnal')
            ->where('jurnal_detail.id_akun', $id_akun)
            ->where('jurnal.tanggal', '<', $from_date)
            ->first();
    }

    public static function getTransaksi($from_date, $to_date, $id_akun)
    {
        if ($from_date == NULL) $from_date = date('Y-m-d');
        if ($to_date == NULL) $to_date = date('Y-m-d');
        return DB::table('jurnal')
            ->select(
                DB::raw('SUM(jurnal_detail.debit) AS debit'),
                DB::raw('SUM(jurnal_detail.kredit) AS kredit')
            )
            ->join('jurnal_detail', 'jurnal_detail.id_jurnal', 'jurnal.id_jurnal')
            ->where('jurnal_detail.id_akun', $id_akun)
            ->whereBetween('jurnal.tanggal', [$from_date, $to_date])
            ->first();
    }
}
