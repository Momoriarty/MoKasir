<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id('id_transaksi_detail');
            $table->foreignId('transaksi_id')->constrained('transaksi', 'id_transaksi')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang', 'id_barang')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga_jual', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
    }
};
