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
        Schema::create('stock_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->string('batch_code')->nullable(); // Kode batch (opsional)
            $table->integer('jumlah_awal'); // Jumlah saat batch dibuat
            $table->integer('jumlah_sisa'); // Jumlah sisa saat ini
            $table->date('tgl_masuk'); // Tanggal batch masuk
            $table->date('tgl_kadaluarsa')->nullable(); // Tanggal kadaluarsa
            $table->enum('status', ['aman', 'hampir_kadaluarsa', 'kadaluarsa'])->default('aman');
            $table->timestamps();

            // Index untuk query yang sering digunakan
            $table->index(['barang_id', 'toko_id']);
            $table->index(['tgl_kadaluarsa']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_batches');
    }
};
