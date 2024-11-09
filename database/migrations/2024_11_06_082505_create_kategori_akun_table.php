<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriAkunTable extends Migration
{
    public function up()
    {
        Schema::create('kategori_akun', function (Blueprint $table) {
            $table->string('id_kategori_akun', 50); // Primary key for kategori_akun, assumed to be string (adjust if needed)
            $table->string('nama_kategori'); // Category name (e.g., "Asset", "Liability", etc.)
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Primary key
            $table->primary('id_kategori_akun');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_akun');
    }
}
