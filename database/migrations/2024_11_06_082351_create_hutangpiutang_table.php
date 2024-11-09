<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHutangpiutangTable extends Migration
{
    public function up()
    {
        Schema::create('hutangpiutang', function (Blueprint $table) {
            $table->string('id_hutangpiutang', 50); // ID for the hutangpiutang (String format, adjust as needed)
            $table->string('id_kontak'); // Contact ID (could be a customer, vendor, etc.)
            $table->string('kategori'); // Category (e.g., 'hutang' for liabilities, 'piutang' for receivables)
            $table->string('jenis'); // Type (e.g., 'utang' or 'pembayaran')
            $table->decimal('nominal', 15, 2); // Amount for the hutang/piutang (decimal for currency)
            $table->string('status'); // Status of the hutang/piutang (e.g., 'lunas', 'belum lunas')
            $table->date('tgl_jatuh_tempo'); // Due date for the hutang/piutang
            $table->timestamps(); // Automatically includes created_at and updated_at columns

            // Primary key
            $table->primary('id_hutangpiutang');

            // Foreign key constraints
            // $table->foreign('id_kontak')->references('id_kontak')->on('kontak');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hutangpiutang');
    }
}

