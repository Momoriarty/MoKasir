<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id('id_transaksi_detail');
            $table->foreignId('id_transaksi')->constrained('transaksis', 'id_transaksi')->cascadeOnDelete();
            $table->foreignId('id_barang')->constrained('barangs', 'id_barang')->cascadeOnDelete();
            $table->integer('jumlah_ecer');
            $table->integer('jumlah_kardus');
            $table->decimal('harga_ecer', 12, 2);    // harga tetap ecer
            $table->decimal('harga_kardus', 12, 2);  // harga tetap kardus
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
