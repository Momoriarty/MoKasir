<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
<<<<<<< HEAD
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

=======
    public function up(): void
    {
        Schema::create('barang_rusaks', function (Blueprint $table) {
            $table->id('id_barang_rusak');
            $table->foreignId('id_barang')->constrained('barangs', 'id_barang')->cascadeOnDelete();
            $table->integer('jumlah_kardus');
            $table->integer('jumlah_ecer');
            $table->string('keterangan', 200)->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }
>>>>>>> fde6c59ba9301da5248afd7988b43e23a5099e89

    public function down(): void
    {
        Schema::dropIfExists('barang_rusaks');
    }
};
