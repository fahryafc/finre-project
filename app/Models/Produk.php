<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    // public $incrementing = false;

    protected $fillable = [
        'id_produk',
        'id_kontak',
        'pemasok',
        'nama_produk',
        'satuan',
        'kategori',
        'kuantitas',
        'kode_sku',
        'tanggal',
        'harga_beli',
        'harga_jual',
        'akun_pembayaran',
        'masuk_akun',
        'kode_reff_pajak',
        'jns_pajak',
        'persen_pajak',
        'nominal_pajak',
        'total_transaksi',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    const CODE_JURNAL = 'PI';

    /**
     * Get the produk_penjualan that owns the Produk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produk_penjualan(): HasMany
    {
        return $this->hasMany(ProdukPenjualan::class, 'id_produk');
    }
}
