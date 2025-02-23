<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaranTable extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->string('id_pengeluaran', 50); // Primary key for pengeluaran, assumed to be string (adjust if needed)
            $table->string('nm_pengeluaran'); // Name or description of the expense
            $table->string('jenis_pengeluaran'); // Type of expense (e.g., 'operasional', 'gaji', 'vendor')
            $table->string('id_kontak'); // Reference to 'kontak' table (id_kontak)
            $table->date('tanggal'); // Date of the expense
            $table->string('kategori'); // Category of the expense
            $table->decimal('biaya', 15, 2); // Expense cost
            $table->decimal('pajak', 15, 2); // Tax associated with the expense
            $table->string('jns_pajak'); // Type of tax (e.g., 'PPN', 'PPh')
            $table->decimal('pajak_persen', 5, 2); // Percentage of tax
            $table->decimal('pajak_dibayarkan', 15, 2); // Tax paid
            $table->boolean('hutang'); // Whether there is a debt associated with the expense (boolean)
            $table->decimal('nominal_hutang', 15, 2)->nullable(); // Debt amount, nullable if no debt
            $table->string('akun_pembayaran'); // Payment account reference
            $table->string('akun_pemasukan'); // Income account reference
            $table->date('tgl_jatuh_tempo'); // Due date for the payment of the debt
            $table->string('user_id');
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Primary key
            $table->primary('id_pengeluaran');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran');
    }
}
