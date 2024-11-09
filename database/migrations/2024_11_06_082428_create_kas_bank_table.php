<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasBankTable extends Migration
{
    public function up()
    {
        Schema::create('kas_bank', function (Blueprint $table) {
            $table->string('id_kas_bank', 50); // Primary key for kas_bank, assumed to be string (adjust if needed)
            $table->string('nama_akun'); // Account name
            $table->string('kode_akun'); // Account code
            $table->string('kategori_akun'); // Category of the account (e.g., asset, liability, etc.)
            $table->string('subakun'); // Sub-account associated with the account
            $table->decimal('saldo', 15, 2)->default(0); // Account balance (decimal with 2 decimal places)
            $table->decimal('uang_masuk', 15, 2)->default(0); // Incoming money (deposits)
            $table->decimal('uang_keluar', 15, 2)->default(0); // Outgoing money (withdrawals)
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Primary key
            $table->primary('id_kas_bank');

            // Foreign key relationships
            // $table->foreign('kategori_akun')->references('id_kategori_akun')->on('kategori_akun');
            // $table->foreign('subakun')->references('id_subakun')->on('subakun');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kas_bank');
    }
}

