<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('nama_barang', 100);
            $table->string('kategori', 100);
            $table->decimal('harga_modal_kardus', 12, 2);
            $table->decimal('harga_modal_ecer', 12, 2);
            $table->decimal('harga_jual_kardus', 12, 2);
            $table->decimal('harga_jual_ecer', 12, 2);
            $table->integer('isi_per_kardus');
            $table->integer('stok_kardus')->default(0);
            $table->integer('stok_ecer')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('barangs');
    }
};
