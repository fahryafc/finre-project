<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanTable extends Migration
{
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->bigIncrements('id_penjualan'); // Auto-increment primary key
            $table->unsignedBigInteger('id_kontak'); // Foreign key
            $table->date('tanggal'); // Date of sale
            $table->string('produk'); // Product name
            $table->string('kategori_produk'); // Product category
            $table->string('satuan'); // Unit of measure
            $table->decimal('harga', 15, 2); // Price of the product
            $table->integer('kuantitas'); // Quantity sold
            $table->decimal('diskon', 15, 2)->default(0); // Discount amount
            $table->decimal('pajak', 15, 2)->default(0); // Tax amount
            $table->decimal('piutang', 15, 2)->default(0); // Receivable amount
            $table->decimal('pembayaran', 15, 2)->default(0); // Payment amount
            $table->decimal('total_pemasukan', 15, 2)->default(0); // Total income
            $table->date('tgl_jatuh_tempo'); // Due date
            $table->string('user_id');
            $table->timestamps(); // Created and updated timestamps

            // Foreign key constraint
            // $table->foreign('id_kontak')->references('id_kontak')->on('kontak');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
}
