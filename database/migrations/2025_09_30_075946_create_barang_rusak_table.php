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
            $table->foreignId('id_barang')->constrained('barangs', 'id_barang')->cascadeOnDelete();
            $table->integer('jumlah_kardus');
            $table->integer('jumlah_ecer');
            $table->string('keterangan', 200)->nullable();
            $table->date('tanggal_rusak');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_rusaks');
    }
};
