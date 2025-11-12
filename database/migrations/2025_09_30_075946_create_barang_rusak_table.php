<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_rusaks', function (Blueprint $table) {
            $table->id('id_barang_rusak');
            $table->foreignId('id_barang')->constrained('barang', 'id_barang')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->string('keterangan', 200)->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_rusak');
    }
};
