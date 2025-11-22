<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->dateTime('tanggal');
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->decimal('total_harga', 12, 2);
            $table->decimal('total_bayar', 12, 2);
            $table->enum('metode', ['Tunai', 'Qris']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
