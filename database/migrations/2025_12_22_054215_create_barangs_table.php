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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('kode_barang')->unique();
            $table->integer('stok')->default(0);
            $table->decimal('harga', 10, 2);
            $table->date('tgl_kadaluwarsa')->nullable();
            $table->enum('status', ['tersedia', 'menipis', 'habis'])->default('tersedia');
            $table->foreignId('kategori_id')->constrained('kategoris', 'kategori_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
