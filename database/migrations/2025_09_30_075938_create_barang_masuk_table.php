<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuks', function (Blueprint $table) {
            $table->id('id_barang_masuk');
            $table->foreignId('id_barang')->constrained('barangs', 'id_barang')->cascadeOnDelete();
            $table->integer('jumlah_kardus')->default(0);
            $table->integer('jumlah_ecer')->default(0);
            $table->date('tanggal_masuk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuks');
    }
};
