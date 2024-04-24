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
        Schema::create('penjualan_api_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualan_api_id'); // Menggunakan unsignedBigInteger sebagai tipe data untuk foreign key
            $table->foreign('penjualan_api_id')->references('id')->on('penjualan_apis')->onDelete('cascade'); // Menambahkan foreign key
            $table->string('item');
            $table->integer('qty');
            $table->float('harga_satuan', 8, 2);
            $table->float('sub_total', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_api_details');
    }
};
