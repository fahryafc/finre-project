<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Jurnal extends Model
{
    use HasFactory;
    protected $table = 'jurnal';
    protected $primaryKey = 'id_jurnal';

    // public $incrementing = false;

    protected $fillable = ['code', 'no_jurnal', 'no_reff', 'tanggal', 'keterangan', 'total', 'status', 'user_id'];

    const TYPE_TANGGAL      = '1';
    const TYPE_HARI_INI     = '2';
    const TYPE_MINGGU_INI   = '3';
    const TYPE_BULAN_INI    = '4';
    const TYPE_TAHUN_INI    = '5';

    public static function typePeriode()
    {
        return [
            ['text' => 'Tanggal', 'value' => self::TYPE_TANGGAL],
            ['text' => 'Hari Ini', 'value' => self::TYPE_HARI_INI],
            ['text' => 'Minggu Ini', 'value' => self::TYPE_MINGGU_INI],
            ['text' => 'Bulan Ini', 'value' => self::TYPE_BULAN_INI],
            ['text' => 'Tahun Ini', 'value' => self::TYPE_TAHUN_INI],
        ];
    }

    public function jurnalDetail()
    {
        return $this->hasMany(JurnalDetail::class, 'id_jurnal', 'id_jurnal');
    }

    public static function getJurnalNo($prefix)
    {
        $tanggal = Carbon::now();
        $period = Carbon::parse($tanggal)->format('ym');
        $lastAccount = Jurnal::select(DB::raw('max(RIGHT(no_jurnal, 4)) as result'))
                        ->whereYear('tanggal',Carbon::parse($tanggal)->format('Y'))
                        ->whereMonth('tanggal',Carbon::parse($tanggal)->format('m'))
                        ->groupByRaw('date_format(tanggal,"%y%m") = "'.$period.'" ')
                        ->orderBy('id_jurnal','desc')
                        ->first();

        if(!empty($lastAccount)){
            $lastNo = $lastAccount->result + 1;
        }else{
            $lastNo = 1;
        }
        $length_no = 4;
        $tmpNo = sprintf('%0'.$length_no.'s', $lastNo);

        return $prefix.Carbon::parse($tanggal)->format('ym').$tmpNo;
    }
}
