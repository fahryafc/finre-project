<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatPembayaranHutangPiutangTable extends Migration
{
    public function up()
    {
        Schema::create('riwayat_pembayaran_hutangpiutang', function (Blueprint $table) {
            $table->string('id_pembayaran_hutangpiutang', 50); // Primary key for the payment history table
            $table->string('id_hutangpiutang'); // Foreign key reference to the hutangpiutang table
            $table->string('jenis_riwayat'); // Type of payment history (e.g., paid, partial)
            $table->date('tanggal_pembayaran'); // Date of the payment
            $table->decimal('dibayarkan', 15, 2); // Amount paid
            $table->decimal('sisa_pembayaran', 15, 2); // Remaining amount to be paid
            $table->string('masuk_akun'); // Account where the payment is recorded
            $table->text('catatan')->nullable(); // Notes or additional information about the payment
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Set the primary key
            $table->primary('id_pembayaran_hutangpiutang');

            // Set the foreign key relationship with the hutangpiutang table
            // $table->foreign('id_hutangpiutang')
            //       ->references('id_hutangpiutang')->on('hutangpiutang')
            //       ->onDelete('cascade'); // If a hutangpiutang record is deleted, also delete related payment history
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_pembayaran_hutangpiutang');
    }
}

