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
        Schema::create('stock_out', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('batch_id')->constrained('stock_batches')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siapa yang input
            $table->integer('jumlah'); // Jumlah barang keluar
            $table->date('tgl_keluar'); // Tanggal barang keluar
            $table->enum('alasan', ['penjualan', 'rusak', 'kadaluarsa', 'retur', 'lainnya'])->default('penjualan');
            $table->text('keterangan')->nullable(); // Catatan tambahan
            $table->timestamps();

            // Index untuk query yang sering digunakan
            $table->index(['barang_id', 'toko_id']);
            $table->index(['tgl_keluar']);
            $table->index(['alasan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_out');
    }
};
