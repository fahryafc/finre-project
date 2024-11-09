<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatuanTable extends Migration
{
    public function up()
    {
        Schema::create('satuan', function (Blueprint $table) {
            $table->string('id_satuan', 50); // Primary key for the satuan table
            $table->string('nama_satuan'); // Name of the unit (e.g., "kilogram", "meter")
            $table->timestamps(); // Created_at and updated_at

            // Set the primary key
            $table->primary('id_satuan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('satuan');
    }
}

