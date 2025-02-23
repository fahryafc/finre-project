<?php

namespace App\Interfaces;
use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\Aset;
use App\Models\AssetPenyusutan;
use App\Models\Modal;
use App\Models\PenjualanAsset;
use App\Models\Produk;
use App\Models\RiwayatPembayaranHutangPiutang;

interface JurnalInterface
{
    public function getAll();

    public function getByTanggal($tanggal_mulai, $tanggal_selesai);

    public function getArusKasByTanggal($tanggal_mulai, $tanggal_selesai);

    public function getNeracaByTanggal($tanggal_mulai, $tanggal_selesai);

    public function getLabaRugiByTanggal($tanggal_mulai, $tanggal_selesai);

    public function delete($id);

    public function storePenjualan(Penjualan $penjualan, $piutang = 0);
    
    public function storePengeluaran(Pengeluaran $pengeluaran);
    
    public function storeAsset(Aset $asset, AssetPenyusutan $asset_penyusutan);
    
    public function storePenjualanAsset(PenjualanAsset $penjualan_asset);
    
    public function storeModal(Modal $modal);
    
    public function storeProduk(Produk $produk);

    public function storePembayaranHutangPiutang(RiwayatPembayaranHutangPiutang $pembayaran);

}
