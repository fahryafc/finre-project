<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukTable extends Migration
{
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->string('id_produk', 50); // Primary key for the produk table
            $table->string('pemasok'); // Supplier's name
            $table->string('no_hp'); // Supplier's phone number
            $table->string('nm_perusahaan'); // Supplier's company name
            $table->string('email'); // Supplier's email address
            $table->text('alamat'); // Supplier's address
            $table->string('nama_produk'); // Product name
            $table->string('satuan'); // Unit of measure for the product
            $table->string('kategori'); // Product category
            $table->integer('kuantitas'); // Quantity of the product in stock
            $table->string('kode_sku'); // SKU (Stock Keeping Unit) code for the product
            $table->date('tanggal'); // Date of the product's entry or manufacture
            $table->decimal('harga_beli', 15, 2); // Purchase price of the product
            $table->decimal('harga_jual', 15, 2); // Selling price of the product
            $table->string('akun_pembayaran'); // Payment account for the product
            $table->string('masuk_akun'); // Account where the product's income goes
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Set the primary key
            $table->primary('id_produk');
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk');
    }
}

