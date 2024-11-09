<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArusUangTable extends Migration
{
    public function up()
    {
        Schema::create('arus_uang', function (Blueprint $table) {
            $table->string('id_uang', 50); // Assuming the ID is a string (can adjust as needed)
            $table->string('kode_akun'); // Account code
            $table->decimal('nominal', 15, 2); // Amount (e.g., in currency)
            $table->string('type'); // Type of transaction (e.g., 'income' or 'expense')
            $table->date('tanggal'); // Date of the transaction
            $table->timestamps();

            // Primary key
            $table->primary('id_uang');

            // Foreign key constraints
            // Assuming kode_akun is referencing a field in another table, such as 'akun'
            // $table->foreign('kode_akun')->references('kode_akun')->on('akun');
        });
    }

    public function down()
    {
        Schema::dropIfExists('arus_uang');
    }
}
