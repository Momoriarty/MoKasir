<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('barang_rusaks', function (Blueprint $table) {
        $table->id('id_barang_rusak');
        $table->unsignedBigInteger('id_barang');
        $table->integer('jumlah_kardus')->default(0);
        $table->integer('jumlah_ecer')->default(0);
        $table->string('keterangan')->nullable();
        $table->date('tanggal_rusak');
        $table->timestamps();

        $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
    });
}


    public function down(): void
    {
        Schema::dropIfExists('barang_rusaks');
    }
};
