<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan_apis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->date('tanggal');
            $table->timeTz('jam');
            $table->float('bayar_tunai', 8, 2);
            $table->float('kembali', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_apis');
    }
};
