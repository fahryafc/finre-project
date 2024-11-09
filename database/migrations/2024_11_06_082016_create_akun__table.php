<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAkunTable extends Migration
{
    public function up()
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->string('id_akun', 50); // Assuming the ID is a string
            $table->string('nama_akun'); // Account name
            $table->string('kode_akun'); // Account code
            $table->unsignedBigInteger('kategori_akun'); // Foreign key for Kategori_akun
            $table->unsignedBigInteger('subakun'); // Foreign key for Subakun
            $table->timestamps();

            // Primary key
            $table->primary('id_akun');

            // Foreign key constraints
            // $table->foreign('kategori_akun')->references('id_kategori_akun')->on('kategori_akun');
            // $table->foreign('subakun')->references('id_subakun')->on('subakun');
        });
    }

    public function down()
    {
        Schema::dropIfExists('akun');
    }
}
