<?php
// di dalam direktori database/migrations

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTable extends Migration
{
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('deskripsi', 255);
            $table->string('nama', 255);
            $table->integer('stok');
            $table->unsignedBigInteger('tipe')->index();
            $table->foreign('tipe')->references('id')->on('tipe');
            $table->string('gambar', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang');
    }
}
