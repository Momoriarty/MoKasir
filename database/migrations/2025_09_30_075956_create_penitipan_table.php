<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penitipan', function (Blueprint $table) {
            $table->id('id_penitipan');
            $table->string('nama_penitip', 100);
            $table->date('tanggal_titip');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('penitipan');
    }
};
