<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jurnal', function (Blueprint $table) {
            $table->bigIncrements('id_jurnal');
            $table->string('code');
            $table->string('no_jurnal');
            $table->string('no_reff');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->string('status');
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal');
    }
};
