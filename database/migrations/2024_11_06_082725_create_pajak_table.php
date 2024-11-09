<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePajakTable extends Migration
{
    public function up()
    {
        Schema::create('Pajak', function (Blueprint $table) {
            $table->string('id_pajak', 50); // Primary key for pajak, assumed to be string (adjust if needed)
            $table->string('nama_produk'); // Name of the product related to tax
            $table->string('gol_pajak'); // Tax type/category (e.g., 'PPN', 'PPh', etc.)
            $table->decimal('total_pajak', 15, 2); // Total tax value for the product
            $table->decimal('persen_pajak', 5, 2); // Percentage of tax applied
            $table->decimal('nominal_pajak', 15, 2); // Nominal value of the tax (calculated amount)
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Primary key
            $table->primary('id_pajak');
        });
    }

    public function down()
    {
        Schema::dropIfExists('Pajak');
    }
}

