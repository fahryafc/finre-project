<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriTable extends Migration
{
    public function up()
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->string('id_kategori', 50); // Primary key for kategori, assumed to be string (adjust if needed)
            $table->string('nama_kategori'); // Category name (e.g., "Product Category", "Asset Category", etc.)
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Primary key
            $table->primary('id_kategori');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori');
    }
}

