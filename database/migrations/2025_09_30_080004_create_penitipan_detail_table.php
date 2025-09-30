<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penitipan_detail', function (Blueprint $table) {
            $table->id('id_penitipan_detail');
            $table->foreignId('penitipan_id')->constrained('penitipan', 'id_penitipan')->cascadeOnDelete();
            $table->string('nama_barang', 100);
            $table->decimal('harga_modal', 12, 2);
            $table->decimal('harga_jual', 12, 2);
            $table->integer('jumlah_titip');
            $table->integer('jumlah_terjual')->default(0);
            $table->integer('jumlah_sisa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penitipan_detail');
    }
};
