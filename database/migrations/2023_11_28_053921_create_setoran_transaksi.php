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
        Schema::create('setoran_transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->foreign('sales_id')->references('id')->on('sales');
            $table->decimal('jumlah_uang', 10, 2);
            $table->date('tanggal_setor');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_transaksi');
    }
};
