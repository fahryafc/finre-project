<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModalTable extends Migration
{
    public function up()
    {
        Schema::create('modal', function (Blueprint $table) {
            $table->string('id_modal', 50); // Primary key for modal, assumed to be string (adjust if needed)
            $table->date('tanggal'); // Date of the transaction
            $table->string('jns_transaksi'); // Type of transaction (e.g., 'income', 'expense', etc.)
            $table->string('nama_badan'); // Name of the entity (e.g., company name)
            $table->decimal('nominal', 15, 2); // Amount for the transaction
            $table->string('masuk_akun'); // Account for the incoming funds (linked to kas_bank or similar)
            $table->string('credit_akun'); // Account for the outgoing funds (linked to kas_bank or similar)
            $table->text('keterangan')->nullable(); // Description of the transaction (optional)
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Primary key
            $table->primary('id_modal');
        });
    }

    public function down()
    {
        Schema::dropIfExists('modal');
    }
}

