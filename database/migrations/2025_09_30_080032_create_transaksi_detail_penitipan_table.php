<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_detail_penitipans', function (Blueprint $table) {
            $table->id('id_transaksi_detail_penitipan');
            $table->foreignId('id_transaksi')->constrained('transaksis', 'id_transaksi')->cascadeOnDelete();
            $table->foreignId('penitipan_detail_id')->constrained('penitipan_details', 'id_penitipan_detail')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga_jual', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail_penitipans');
    }
};
