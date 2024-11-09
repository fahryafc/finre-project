<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontakTable extends Migration
{
    public function up()
    {
        Schema::create('kontak', function (Blueprint $table) {
            $table->string('id_kontak', 50); // Primary key for kontak, assumed to be string (adjust if needed)
            $table->string('jenis_kontak');  // Type of contact (e.g., 'Customer', 'Supplier', etc.)
            $table->string('nama_kontak');   // Contact name
            $table->string('email')->nullable(); // Contact email
            $table->string('no_hp')->nullable(); // Contact phone number
            $table->string('nm_perusahaan')->nullable(); // Company name, if applicable
            $table->text('alamat')->nullable(); // Address
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Primary key
            $table->primary('id_kontak');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kontak');
    }
}

