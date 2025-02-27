<?php

namespace App\Repositories;

use App\Interfaces\JurnalInterface;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\Akun;
use App\Models\Aset;
use App\Models\AssetPenyusutan;
use App\Models\Modal;
use App\Models\PenjualanAsset;
use App\Models\Produk;
use App\Models\RiwayatPembayaranHutangPiutang;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JurnalRepository implements JurnalInterface
{
    public function getAll()
    {
        $jurnals = Jurnal::get();
        return $jurnals;
    }

    public function getByTanggal($tanggal_mulai, $tanggal_selesai)
    {
        $user_id = 1; // Auth::user()->id;
        $jurnals = Jurnal::whereBetween('tanggal', [$tanggal_mulai, $tanggal_selesai])
                ->where('user_id', $user_id)
                ->get();
                
        return $jurnals;
    }

    public function getArusKasByTanggal($tanggal_mulai, $tanggal_selesai)
    {
        $user_id = 1; // Auth::user()->id;
        
        $result = [];

        $j = DB::table('jurnal_detail')                    
                    ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                    ->join('akun', 'akun.id_akun', '=', 'jurnal_detail.id_akun')
                    ->where('jurnal.user_id', $user_id)
                    ->whereBetween('jurnal.tanggal', [$tanggal_mulai, $tanggal_selesai]);

        $operasional[] = $j->selectRaw("'Penerimaan Kas dari pelanggan' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->where('kode_akun', '4-101')->first();
        $operasional[] = $j->selectRaw("'Pendapatan lain-lain' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->where('kode_akun', '4-104')->first();
        $operasional[] = $j->selectRaw("'Pembayaran kas kepada vendor' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->whereIn('kode_akun', ['5-103','5-104','5-105','5-106','5-107','5-108','5-109'])->first();
        $operasional[] = $j->selectRaw("'Pembayaran kas kepada karyawan' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->where('kode_akun', '5-102')->first();
        $operasional[] = $j->selectRaw("'Pembelian persediaan barang' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->where('kode_akun', '5-101')->first();
        $operasional[] = $j->selectRaw("'Pembayaran pajak penghasilan' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->where('kode_akun', '2-108')->first();
        $operasional[] = $j->selectRaw("'Penerimaan Pajak Keluaran' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->whereIn('kode_akun', ['2-106','2-107'])->first();
        $operasional[] = $j->selectRaw("'Pembayaran Pajak Masukan' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->whereIn('kode_akun', ['1-109','1-110'])->first();
        $operasional[] = $j->selectRaw("'Pembayaran Bunga Pinjaman' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->where('kode_akun', '5-116')->first();

        $investasi[] = $j->selectRaw("'Pembelian aset tetap' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->whereIn('kode_akun', ['1-201','1-202','1-203','1-204','1-205','1-206','1-207'])->first();

        $pendanaan[] = $j->selectRaw("'Penerimaan modal' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->whereIn('kode_akun', ['3-101','3-103'])->first();
        $pendanaan[] = $j->selectRaw("'Pembayaran dividen' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->where('kode_akun', '3-105')->first();
        $pendanaan[] = $j->selectRaw("'Penerimaan Pinjaman' as aktivitas,SUM(debit) as debit,SUM(kredit) as kredit")->whereIn('kode_akun', ['2-101','2-102'])->first();

        $result = [
            'operasional' => $operasional,
            'investasi' => $investasi,
            'pendanaan' => $pendanaan,
        ];

        return $result;
    }

    public function getNeracaByTanggal($tanggal_mulai, $tanggal_selesai)
    {
        $user_id = 1; // Auth::user()->id;
        $akun_neraca = Akun::whereIn('kategori_akun', ['Aset/Harta','Utang/Kewajiban/Liabilitas','Modal/Ekuitas (Kekayaan Peusahaan)'])->get();
        $neracas = [];
        foreach ($akun_neraca as $akun) {    
            $total = DB::table('jurnal_detail')
                    ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                    ->where('jurnal.user_id', $user_id)
                    ->where('jurnal_detail.id_akun', $akun->id_akun)
                    ->whereBetween('jurnal.tanggal', [$tanggal_mulai, $tanggal_selesai])
                    ->sum('debit');

            if ($total > 0) {
                $neracas[$akun->kategori_akun][$akun->subakun][] = [
                    'kode_akun' => $akun->kode_akun,
                    'nama_akun' => $akun->nama_akun,
                    'total' => $total,
                ];
            }    
        }
 
        return $neracas;
    }

    public function getLabaRugiByTanggal($tanggal_mulai, $tanggal_selesai)
    {
        $user_id = 1; // Auth::user()->id;
        $akun_pendapatan = Akun::whereIn('kategori_akun', ['Pendapatan'])->get();
        $pendapatans = [];
        foreach ($akun_pendapatan as $akun) {    
            $total = DB::table('jurnal_detail')
                    ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                    ->where('jurnal.user_id', $user_id)
                    ->where('jurnal_detail.id_akun', $akun->id_akun)
                    ->whereBetween('jurnal.tanggal', [$tanggal_mulai, $tanggal_selesai])
                    ->sum('debit');

            if ($total > 0) {
                $pendapatans[] = [
                    'nama_akun' => $akun->nama_akun,
                    'total' => $total,
                ];
            }    
        }

        $akun_hpp = Akun::where('kode_akun', '5-101')->first();
        $hpps[] = [
            'nama_akun' => $akun_hpp->nama_akun,
            'total' => DB::table('jurnal_detail')
                            ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                            ->where('jurnal.user_id', $user_id)
                            ->where('jurnal_detail.id_akun', $akun_hpp->id_akun)
                            ->whereBetween('jurnal.tanggal', [$tanggal_mulai, $tanggal_selesai])
                            ->sum('debit'),
        ];

        $akun_beban = Akun::whereIn('kategori_akun', ['Beban'])->whereNotIn('kode_akun', ['5-101'])->get();
        $bebans = [];
        foreach ($akun_beban as $akun) {    
            $total = DB::table('jurnal_detail')
                    ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                    ->where('jurnal.user_id', $user_id)
                    ->where('jurnal_detail.id_akun', $akun->id_akun)
                    ->whereBetween('jurnal.tanggal', [$tanggal_mulai, $tanggal_selesai])
                    ->sum('debit');

            if ($total > 0) {
                $bebans[] = [
                    'nama_akun' => $akun->nama_akun,
                    'total' => $total,
                ];
            }    
        }

        $akun_pendapatan_lain = Akun::where('kode_akun', '4-104')->first();
        $total_pendapatan_lain = DB::table('jurnal_detail')
                            ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                            ->where('jurnal.user_id', $user_id)
                            ->where('jurnal_detail.id_akun', $akun_pendapatan_lain->id_akun)
                            ->whereBetween('jurnal.tanggal', [$tanggal_mulai, $tanggal_selesai])
                            ->sum('debit');

        $akun_biaya_lain = Akun::where('kode_akun', '5-109')->first();
        $total_biaya_lain = DB::table('jurnal_detail')
                            ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                            ->where('jurnal.user_id', $user_id)
                            ->where('jurnal_detail.id_akun', $akun_biaya_lain->id_akun)
                            ->whereBetween('jurnal.tanggal', [$tanggal_mulai, $tanggal_selesai])
                            ->sum('debit');

        $result = [
            'pendapatans' => $pendapatans,
            'hpps' => $hpps,
            'bebans' => $bebans,
            'lains' => $total_pendapatan_lain - $total_biaya_lain,
        ];
 
        return $result;
    }

    public function getNextNumber($prefix)
    {
        return Jurnal::getJurnalNo($prefix);
    }

    public function delete($id)
    {
        $jurnal = Jurnal::find($id);
        JurnalDetail::where('id_jurnal', $jurnal->id_jurnal)->delete();
        $jurnal->delete();

        return $jurnal;
    }

    public function storePenjualan(Penjualan $penjualan, $piutang = 0)
    {
        $prefix = Penjualan::CODE_JURNAL;
        $jurnal = Jurnal::where('code',$prefix)->where('no_reff', $penjualan->id_penjualan)->first();
        if ($jurnal) {
            $this->delete($jurnal->id_jurnal);
        }
        
        $total_penjualan = $penjualan->total_pemasukan + $penjualan->total_pajak + $penjualan->ongkir;
        $data = [
            'no_jurnal'        => $this->getNextNumber($prefix),
            'code'             => $prefix,
            'no_reff'          => $penjualan->id_penjualan,
            'tanggal'          => $penjualan->tanggal,
            'keterangan'       => 'Jurnal Penjualan',
            'total'            => $total_penjualan,
            'status'           => '',
            'user_id'          => 1, // Auth::user()->id,
        ];
        
        $jurnal = Jurnal::create($data);

        // Insert jurnal Penjualan
        $akun_kas = Akun::where('kode_akun',$penjualan->pembayaran)->first();
        if ($akun_kas) {
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_kas->id_akun,
                'debit'           => $total_penjualan - $piutang,
                'kredit'          => 0,
                'keterangan'      => 'Kas/Bank',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        } else{
            throw new \Exception('Akun Kas/Bank tidak ditemukan!');
        }

        // Insert jurnal piutang usaha
        if ($penjualan->piutang == 1) {
            $akun_piutang_usaha = Akun::where('kode_akun','1-103')->first();
            if ($akun_piutang_usaha) {
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_piutang_usaha->id_akun,
                    'debit'           => $piutang,
                    'kredit'          => 0,
                    'keterangan'      => 'Piutang Usaha',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            } else{
                throw new \Exception('Akun Piutang Usaha tidak ditemukan!');
            }
        }

        // Insert jurnal pendapatan barang/jasa
        if ($penjualan->total_pemasukan) {
            $akun_pendapatan_barang = Akun::where('kode_akun','4-101')->first();
            if ($akun_pendapatan_barang) {
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pendapatan_barang->id_akun,
                    'debit'           => 0,
                    'kredit'          => $penjualan->total_pemasukan,
                    'keterangan'      => 'Pendapatan Barang/Jasa',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            } else{
                throw new \Exception('Akun Pendapatan Barang/Jasa tidak ditemukan!');
            }
        }

        // Insert jurnal pendapatan biaya pengiriman
        if ($penjualan->ongkir) {
            $akun_pendapatan_ongkir = Akun::where('kode_akun','4-103')->first();
            if ($akun_pendapatan_ongkir) {
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pendapatan_ongkir->id_akun,
                    'debit'           => 0,
                    'kredit'          => $penjualan->ongkir,
                    'keterangan'      => 'Pendapatan Biaya Pengiriman',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            } else{
                throw new \Exception('Akun Pendapatan Biaya Pengiriman tidak ditemukan!');
            }
        }

        $total_pajak_ppn = 0;
        $total_pajak_ppnbm = 0;
        foreach ($penjualan->produkPenjualan as $key => $value) {
            // Menambahkan pajak jika ada
            if ($value->jns_pajak) {
                if ($value->jns_pajak == 'ppn11' || $value->jns_pajak == 'ppn12') {
                    $total_pajak_ppn = $total_pajak_ppn + ($value->harga * $value->kuantitas * ($value->persen_pajak / 100));
                } elseif ($value->jns_pajak == 'ppnbm') {
                    $total_pajak_ppnbm = $total_pajak_ppnbm + ($value->harga * $value->kuantitas * ($value->persen_pajak / 100));
                }
            }
        }

        // Insert jurnal pajak untuk PPN Keluaran
        if ($total_pajak_ppn > 0) {
            $akun_pajak_ppn = Akun::where('kode_akun','2-106')->first();
            if ($akun_pajak_ppn) {
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pajak_ppn->id_akun,
                    'debit'           => 0,
                    'kredit'          => $total_pajak_ppn,
                    'keterangan'      => 'PPN Keluaran',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            } else{
                throw new \Exception('Akun PPN Keluaran tidak ditemukan!');
            }
        }

        // Insert jurnal pajak untuk PPNBM Keluaran
        if ($total_pajak_ppnbm > 0) {
            $akun_pajak_ppnbm = Akun::where('kode_akun','2-107')->first();
            if ($akun_pajak_ppnbm) {
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pajak_ppnbm->id_akun,
                    'debit'           => 0,
                    'kredit'          => $total_pajak_ppnbm,
                    'keterangan'      => 'PPNBM Keluaran',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            } else{
                throw new \Exception('Akun PPNBM Keluaran tidak ditemukan!');
            }
        }

        return $jurnal;   
    }

    public function storePengeluaran(Pengeluaran $pengeluaran)
    {
        $prefix = Pengeluaran::CODE_JURNAL;
        $jurnal = Jurnal::where('code',$prefix)->where('no_reff', $pengeluaran->id_pengeluaran)->first();
        if ($jurnal) {
            $this->delete($jurnal->id_jurnal);
        }

        $data = [
            'no_jurnal'        => $this->getNextNumber($prefix),
            'code'             => $prefix,
            'no_reff'          => $pengeluaran->id_pengeluaran,
            'tanggal'          => $pengeluaran->tanggal,
            'keterangan'       => 'Jurnal Pengeluaran',
            'total'            => $pengeluaran->biaya,
            'status'           => '',
            'user_id'          => 1, // Auth::user()->id,
        ];
        
        $jurnal = Jurnal::create($data);

        // Insert jurnal hutang usaha
        if ($pengeluaran->biaya) {
            $akun_pemasukan = Akun::where('kode_akun',$pengeluaran->akun_pemasukan)->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_pemasukan->id_akun,
                'debit'           => $pengeluaran->biaya - $pengeluaran->pajak_dibayarkan - $pengeluaran->nominal_hutang,
                'kredit'          => 0,
                'keterangan'      => 'Hutang Usaha',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        }

        // Insert jurnal Pengeluaran
        $akun_kas = Akun::where('kode_akun',$pengeluaran->akun_pembayaran)->first();
        DB::table('jurnal_detail')->insert([
            'id_jurnal'       => $jurnal->id_jurnal,
            'id_akun'         => $akun_kas->id_akun,
            'debit'           => 0,
            'kredit'          => $pengeluaran->biaya,
            'keterangan'      => 'Kas/Bank',
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ]);        

        // Insert jurnal beban yang harus dibayar
        if ($pengeluaran->hutang == 1) {
            $akun_hutang_usaha = Akun::where('kode_akun','2-104')->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_hutang_usaha->id_akun,
                'debit'           => $pengeluaran->nominal_hutang,
                'kredit'          => 0,
                'keterangan'      => 'Beban Yang masih harus dibayar',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        }        

        if ($pengeluaran->pajak == 1) {
            if ($pengeluaran->jns_pajak == 'ppn') {
                // Insert jurnal pajak untuk PPN Masukan
                $akun_pajak_ppn = Akun::where('kode_akun','1-109')->first();
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pajak_ppn->id_akun,
                    'debit'           => $pengeluaran->pajak_dibayarkan,
                    'kredit'          => 0,
                    'keterangan'      => 'PPN Masukan',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            } elseif ($pengeluaran->jns_pajak == 'ppnbm') {
                // Insert jurnal pajak untuk PPNBM Masukan
                $akun_pajak_ppnbm = Akun::where('kode_akun','1-110')->first();
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pajak_ppnbm->id_akun,
                    'debit'           => $pengeluaran->pajak_dibayarkan,
                    'kredit'          => 0,
                    'keterangan'      => 'PPNBM Masukan',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            } elseif ($pengeluaran->jns_pajak == 'pph') {
                // Insert jurnal pajak untuk PPH
                $akun_pajak_pph = Akun::where('kode_akun','2-108')->first();
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pajak_pph->id_akun,
                    'debit'           => $pengeluaran->pajak_dibayarkan,
                    'kredit'          => 0,
                    'keterangan'      => 'Hutang PPH 21',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            }
        }

        return $jurnal;   
    }

    public function storeAsset(Aset $aset, AssetPenyusutan $asset_penyusutan)
    {
        $prefix = Aset::CODE_JURNAL;
        $jurnal = Jurnal::where('code',$prefix)->where('no_reff', $aset->id_aset)->first();
        if ($jurnal) {
            $this->delete($jurnal->id_jurnal);
        }

        $total_harga = ($aset->harga_beli * $aset->kuantitas) + $aset->pajak_dibayarkan;
        $data = [
            'no_jurnal'        => $this->getNextNumber($prefix),
            'code'             => $prefix,
            'no_reff'          => $aset->id_aset,
            'tanggal'          => $aset->tanggal,
            'keterangan'       => 'Jurnal Asset',
            'total'            => $total_harga,
            'status'           => '',
            'user_id'          => 1, // Auth::user()->id,
        ];
        
        $jurnal = Jurnal::create($data);

        // Insert jurnal Asset
        if ($aset->akun_aset) {
            $akun_asset = Akun::where('kode_akun',$aset->akun_aset)->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_asset->id_akun,
                'debit'           => $total_harga,
                'kredit'          => 0,
                'keterangan'      => 'Asset',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        }

        // Insert jurnal Pengeluaran
        $akun_pembayaran = Akun::where('kode_akun',$aset->akun_pembayaran)->first();
        DB::table('jurnal_detail')->insert([
            'id_jurnal'       => $jurnal->id_jurnal,
            'id_akun'         => $akun_pembayaran->id_akun,
            'debit'           => 0,
            'kredit'          => $aset->harga_beli * $aset->kuantitas,
            'keterangan'      => 'Kas/Bank',
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ]);       
        
        if ($aset->penyusutan == 1 && $asset_penyusutan) {
            if ($asset_penyusutan->akun_penyusutan) {
                // Insert jurnal Penyusutan
                $akun_penyusutan = Akun::where('kode_akun',$asset_penyusutan->akun_penyusutan)->first();
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_penyusutan->id_akun,
                    'debit'           => ($asset_penyusutan->nominal_masa_manfaat) ? $asset_penyusutan->nominal_masa_manfaat * $asset_penyusutan->masa_manfaat : $asset_penyusutan->nominal_nilai_tahun * $asset_penyusutan->nilai_tahun,
                    'kredit'          => 0,
                    'keterangan'      => 'Penyusutan',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            }

            if ($asset_penyusutan->akumulasi_akun) {
                // Insert jurnal Akumulasi Akun
                $akun_akumulasi = Akun::where('kode_akun',$asset_penyusutan->akun_akumulasi)->first();
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_akumulasi->id_akun,
                    'debit'           => 0,
                    'kredit'          => $asset_penyusutan->akumulasi_akun,
                    'keterangan'      => 'Akumulasi Akun',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            }
        }

        if ($aset->pajak == 1) {
            if ($aset->jns_pajak == 'ppn11' || $aset->jns_pajak == 'ppn12') {
                // Insert jurnal pajak untuk PPN Keluaran
                $akun_pajak_ppn = Akun::where('kode_akun','2-106')->first();
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pajak_ppn->id_akun,
                    'debit'           => 0,
                    'kredit'          => $aset->pajak_dibayarkan,
                    'keterangan'      => 'PPN Keluaran',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            } elseif ($aset->jns_pajak == 'ppnbm') {
                // Insert jurnal pajak untuk PPNBM
                $akun_pajak_ppnbm = Akun::where('kode_akun','2-107')->first();
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pajak_ppnbm->id_akun,
                    'debit'           => 0,
                    'kredit'          => $aset->pajak_dibayarkan,
                    'keterangan'      => 'PPNBM Keluaran',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            }
        }

        return $jurnal;   
    }

    public function storePenjualanAsset(PenjualanAsset $penjualan_asset)
    {
        $prefix = PenjualanAsset::CODE_JURNAL;
        $jurnal = Jurnal::where('code',$prefix)->where('no_reff', $penjualan_asset->id_penjualan_asset)->first();
        if ($jurnal) {
            $this->delete($jurnal->id_jurnal);
        }

        $total_transaksi = $penjualan_asset->nominal_deposit + $penjualan_asset->pajak_dibayarkan;
        $data = [
            'no_jurnal'        => $this->getNextNumber($prefix),
            'code'             => $prefix,
            'no_reff'          => $penjualan_asset->id_penjualan_asset,
            'tanggal'          => $penjualan_asset->tgl_penjualan,
            'keterangan'       => 'Jurnal Penjualan Asset',
            'total'            => $total_transaksi,
            'status'           => '',
            'user_id'          => 1, // Auth::user()->id,
        ];
        
        $jurnal = Jurnal::create($data);

        // Insert jurnal Deposit
        $akun_deposit = Akun::where('kode_akun',$penjualan_asset->akun_deposit)->first();
        DB::table('jurnal_detail')->insert([
            'id_jurnal'       => $jurnal->id_jurnal,
            'id_akun'         => $akun_deposit->id_akun,
            'debit'           => $total_transaksi,
            'kredit'          => 0,
            'keterangan'      => 'Deposit',
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ]);  

        // Insert jurnal Nilai Asset
        $akun_aset = Akun::where('kode_akun',$penjualan_asset->asset->akun_aset)->first();
        DB::table('jurnal_detail')->insert([
            'id_jurnal'       => $jurnal->id_jurnal,
            'id_akun'         => $akun_aset->id_akun,
            'debit'           => ($penjualan_asset->asset->harga_beli * $penjualan_asset->asset->kuantitas),
            'kredit'          => 0,
            'keterangan'      => 'Deposit',
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ]);  

        if ($penjualan_asset->nominal_keuntungan_kerugian > 0) {
            // Insert jurnal Keuntungan
            $akun_keuntungan_kerugian = Akun::where('kode_akun',$penjualan_asset->akun_keuntungan_kerugian)->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_keuntungan_kerugian->id_akun,
                'debit'           => 0,
                'kredit'          => $penjualan_asset->nominal_keuntungan_kerugian,
                'keterangan'      => 'Keuntungan',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]); 
        } else{
            // Insert jurnal Kerugian
            $akun_keuntungan_kerugian = Akun::where('kode_akun',$penjualan_asset->akun_keuntungan_kerugian)->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_keuntungan_kerugian->id_akun,
                'debit'           => $penjualan_asset->nominal_keuntungan_kerugian,
                'kredit'          => 0,
                'keterangan'      => 'Kerugian',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        }

        if ($penjualan_asset->pajak == 1) {
            if ($penjualan_asset->jns_pajak == 'ppn11' || $penjualan_asset->jns_pajak == 'ppn12') {
                // Insert jurnal pajak untuk PPN Keluaran
                $akun_pajak_ppn = Akun::where('kode_akun','2-106')->first();
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pajak_ppn->id_akun,
                    'debit'           => 0,
                    'kredit'          => $penjualan_asset->pajak_dibayarkan,
                    'keterangan'      => 'PPN Keluaran',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            } elseif ($penjualan_asset->jns_pajak == 'ppnbm') {
                // Insert jurnal pajak untuk PPNBM
                $akun_pajak_ppnbm = Akun::where('kode_akun','2-107')->first();
                DB::table('jurnal_detail')->insert([
                    'id_jurnal'       => $jurnal->id_jurnal,
                    'id_akun'         => $akun_pajak_ppnbm->id_akun,
                    'debit'           => 0,
                    'kredit'          => $penjualan_asset->pajak_dibayarkan,
                    'keterangan'      => 'PPNBM Keluaran',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            }
        }       

        return $jurnal;   
    }

    public function storeModal(Modal $modal)
    {
        $prefix = Modal::CODE_JURNAL;
        $jurnal = Jurnal::where('code',$prefix)->where('no_reff', $modal->id_modal)->first();
        if ($jurnal) {
            $this->delete($jurnal->id_jurnal);
        }

        $data = [
            'no_jurnal'        => $this->getNextNumber($prefix),
            'code'             => $prefix,
            'no_reff'          => $modal->id_modal,
            'tanggal'          => $modal->tanggal,
            'keterangan'       => 'Jurnal Modal',
            'total'            => $modal->nominal,
            'status'           => '',
            'user_id'          => 1, // Auth::user()->id,
        ];
        
        $jurnal = Jurnal::create($data);

        // Insert jurnal Masuk akun
        if ($modal->jns_transaksi === 'Penyetoran Modal') {
            $akun_masuk = Akun::where('kode_akun',$modal->masuk_akun)->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_masuk->id_akun,
                'debit'           => $modal->nominal,
                'kredit'          => 0,
                'keterangan'      => 'Masuk Akun',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);

            // Insert jurnal Modal Pemilik
            $akun_modal = Akun::where('kode_akun','3-101')->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_modal->id_akun,
                'debit'           => 0,
                'kredit'          => $modal->nominal,
                'keterangan'      => 'Modal Pemilik',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        }

        // Insert jurnal Credit Akun
        if ($modal->jns_transaksi === 'Penarikan Dividen') {
            $akun_credit = Akun::where('kode_akun',$modal->credit_akun)->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_credit->id_akun,
                'debit'           => 0,
                'kredit'          => $modal->nominal,
                'keterangan'      => 'Kredit Akun',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);

            // Insert jurnal Penarikan Dividen
            $akun_modal = Akun::where('kode_akun','2-109')->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_modal->id_akun,
                'debit'           => $modal->nominal,
                'kredit'          => 0,
                'keterangan'      => 'Penarikan Dividen',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        }        

        return $jurnal;   
    }

    public function storeProduk(Produk $produk)
    {
        $prefix = Produk::CODE_JURNAL;
        $jurnal = Jurnal::where('code',$prefix)->where('no_reff', $produk->id_produk)->first();
        if ($jurnal) {
            $this->delete($jurnal->id_jurnal);
        }

        $total_transaksi = ($produk->kuantitas * $produk->harga_beli) + $produk->nominal_pajak;
        $data = [
            'no_jurnal'        => $this->getNextNumber($prefix),
            'code'             => $prefix,
            'no_reff'          => $produk->id_produk,
            'tanggal'          => $produk->tanggal,
            'keterangan'       => 'Jurnal Produk dan Inventory',
            'total'            => $total_transaksi,
            'status'           => '',
            'user_id'          => 1, // Auth::user()->id,
        ];
        
        $jurnal = Jurnal::create($data);

        // Insert jurnal Persediaan barang dagang
        $akun_persediaan = Akun::where('kode_akun','1-108')->first();
        DB::table('jurnal_detail')->insert([
            'id_jurnal'       => $jurnal->id_jurnal,
            'id_akun'         => $akun_persediaan->id_akun,
            'debit'           => $total_transaksi,
            'kredit'          => 0,
            'keterangan'      => 'Persediaan barang dagang',
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ]);

        // Insert jurnal Pembayaran
        $akun_pembayaran = Akun::where('kode_akun',$produk->akun_pembayaran)->first();
        DB::table('jurnal_detail')->insert([
            'id_jurnal'       => $jurnal->id_jurnal,
            'id_akun'         => $akun_pembayaran->id_akun,
            'debit'           => 0,
            'kredit'          => $produk->kuantitas * $produk->harga_beli,
            'keterangan'      => 'Kas/Bank',
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ]);  

        if ($produk->jns_pajak == 'ppn') {
            // Insert jurnal pajak untuk PPN Masukan
            $akun_pajak_ppn = Akun::where('kode_akun','1-109')->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_pajak_ppn->id_akun,
                'debit'           => 0,
                'kredit'          => $produk->nominal_pajak,
                'keterangan'      => 'PPN Masukan',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        } elseif ($produk->jns_pajak == 'ppnbm') {
            // Insert jurnal pajak untuk PPNBM
            $akun_pajak_ppnbm = Akun::where('kode_akun','2-107')->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_pajak_ppnbm->id_akun,
                'debit'           => 0,
                'kredit'          => $produk->nominal_pajak,
                'keterangan'      => 'PPNBM Masukan',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        }   

        return $jurnal;   
    }

    public function storePembayaranHutangPiutang(RiwayatPembayaranHutangPiutang $pembayaran)
    {
        $prefix = RiwayatPembayaranHutangPiutang::CODE_JURNAL;
        $jurnal = Jurnal::where('code',$prefix)->where('no_reff', $pembayaran->id_pembayaran_hutangpiutang)->first();
        if ($jurnal) {
            $this->delete($jurnal->id_jurnal);
        }

        $data = [
            'no_jurnal'        => $this->getNextNumber($prefix),
            'code'             => $prefix,
            'no_reff'          => $pembayaran->id_pembayaran_hutangpiutang,
            'tanggal'          => $pembayaran->tanggal_pembayaran,
            'keterangan'       => 'Jurnal Pembayaran Hutang & Piutang',
            'total'            => $pembayaran->dibayarkan,
            'status'           => '',
            'user_id'          => 1, // Auth::user()->id,
        ];
        
        $jurnal = Jurnal::create($data);

        if ($pembayaran->jenis_riwayat == 'hutang') {
            // Insert jurnal Hutang
            $akun_hutang = Akun::where('kode_akun','2-101')->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_hutang->id_akun,
                'debit'           => $pembayaran->dibayarkan,
                'kredit'          => 0,
                'keterangan'      => 'Utang Usaha',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]); 

            // Insert jurnal Pembayaran Masuk
            $akun_keluar = Akun::where('kode_akun',$pembayaran->masuk_akun)->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_keluar->id_akun,
                'debit'           => 0,
                'kredit'          => $pembayaran->dibayarkan,
                'keterangan'      => 'Pembayaran Keluar',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        } else{
            // Insert jurnal Pembayaran Masuk
            $akun_masuk = Akun::where('kode_akun',$pembayaran->masuk_akun)->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_masuk->id_akun,
                'debit'           => $pembayaran->dibayarkan,
                'kredit'          => 0,
                'keterangan'      => 'Pembayaran Masuk',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);

            // Insert jurnal Piutang
            $akun_piutang = Akun::where('kode_akun','1-103')->first();
            DB::table('jurnal_detail')->insert([
                'id_jurnal'       => $jurnal->id_jurnal,
                'id_akun'         => $akun_piutang->id_akun,
                'debit'           => 0,
                'kredit'          => $pembayaran->dibayarkan,
                'keterangan'      => 'Piutang',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]); 
        }

        return $jurnal;   
    }
}