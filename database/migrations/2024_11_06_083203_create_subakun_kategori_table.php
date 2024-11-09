<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubakunKategoriTable extends Migration
{
    public function up()
    {
        Schema::create('subakun_kategori', function (Blueprint $table) {
            $table->string('id_subakun', 50); // Primary key for subakun_kategori table
            $table->string('id_kategori_akun', 50); // Foreign key referencing the id_kategori_akun from kategori_akun table
            $table->string('kode'); // Code for the subaccount
            $table->string('nama_subakun'); // Name of the subaccount
            $table->timestamps(); // Created_at and updated_at

            // Define the primary key
            $table->primary('id_subakun');

            // Foreign key relationship to kategori_akun table
            // $table->foreign('id_kategori_akun')->references('id_kategori_akun')->on('kategori_akun')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subakun_kategori');
    }
}
