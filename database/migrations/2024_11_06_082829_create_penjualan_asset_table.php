<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanAssetTable extends Migration
{
    public function up()
    {
        Schema::create('penjualan_asset', function (Blueprint $table) {
            $table->string('id_penjualan_asset', 50); // Primary key for the penjualan_asset table
            $table->string('id_aset'); // Foreign key reference to the aset table (id_aset)
            $table->string('nm_pelanggan'); // Customer name
            $table->string('nm_perusahaan'); // Company name
            $table->string('no_hp'); // Customer phone number
            $table->enum('gender', ['male', 'female', 'other']); // Gender of the customer
            $table->string('email'); // Customer email
            $table->text('alamat'); // Customer address
            $table->integer('kuantitas'); // Quantity of assets sold
            $table->date('tgl_penjualan'); // Date of sale
            $table->decimal('harga_pelepasan', 15, 2); // Sale price of the asset
            $table->decimal('nilai_penyusutan_terakhir', 15, 2); // Last depreciation value
            $table->decimal('nilai_buku', 15, 2); // Book value of the asset at the time of sale
            $table->string('akun_deposit'); // Account for the deposit transaction
            $table->decimal('nominal_deposit', 15, 2); // Deposit amount
            $table->string('akun_keuntungan_kerugian'); // Account for profit/loss from the sale
            $table->decimal('nominal_keuntungan_kerugian', 15, 2); // Profit or loss amount
            $table->string('kategori'); // Category of the asset being sold
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Set the primary key
            $table->primary('id_penjualan_asset');

            // Add foreign key relationship with the aset table
            // $table->foreign('id_aset')->references('id_aset')->on('asset')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualan_asset');
    }
}

