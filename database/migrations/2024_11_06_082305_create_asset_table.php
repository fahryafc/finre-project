<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTable extends Migration
{
    public function up()
    {
        Schema::create('asset', function (Blueprint $table) {
            $table->string('id_aset', 50); // Asset ID (String format, adjust if needed)
            $table->string('pemasok'); // Supplier name
            $table->string('no_hp'); // Supplier phone number
            $table->string('nm_perusahaan'); // Supplier company name
            $table->string('email'); // Supplier email
            $table->text('alamat'); // Supplier address
            $table->date('tanggal'); // Date of asset acquisition
            $table->string('nm_aset'); // Asset name
            $table->string('satuan'); // Unit of the asset (e.g., pieces, kilograms)
            $table->integer('kuantitas'); // Quantity of assets
            $table->decimal('pajak', 15, 2); // Tax amount for the asset
            $table->string('jns_pajak'); // Type of tax (e.g., VAT, sales tax)
            $table->decimal('persen_pajak', 5, 2); // Percentage rate for tax
            $table->decimal('pajak_dibayarkan', 15, 2); // Amount of tax paid
            $table->string('kode_sku'); // Stock keeping unit (SKU) code for the asset
            $table->decimal('harga_beli', 15, 2); // Purchase price of the asset
            $table->string('akun_pembayaran'); // Account code for payment
            $table->string('akun_aset'); // Account code for the asset
            $table->boolean('penyusutan'); // Depreciation flag (1 if depreciable, 0 if not)
            $table->string('kategori'); // Asset category (e.g., office equipment, machinery)
            $table->string('user_id');
            $table->timestamps();

            // Primary key
            $table->primary('id_aset');

            // Foreign key constraints
            // $table->foreign('akun_pembayaran')->references('kode_akun')->on('akun');
            // $table->foreign('akun_aset')->references('kode_akun')->on('akun');
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset');
    }
}

