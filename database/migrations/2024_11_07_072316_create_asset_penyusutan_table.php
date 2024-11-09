<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetPenyusutanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_penyusutan', function (Blueprint $table) {
            $table->id('id_penyusutan');
            $table->unsignedBigInteger('id_aset');
            $table->integer('masa_manfaat')->nullable();
            $table->decimal('nilai_tahun', 8, 2)->nullable();
            $table->decimal('nominal_masa_manfaat', 15, 2)->nullable();
            $table->decimal('nominal_nilai_tahun', 15, 2)->nullable();
            $table->unsignedBigInteger('akun_penyusutan')->nullable();
            $table->decimal('akumulasi_akun', 15, 2)->nullable();
            $table->timestamps();

            // Foreign key constraint
            // $table->foreign('id_aset')->references('id_aset')->on('aset')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_penyusutan');
    }
}
