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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kode_transaksi')->unique();
            $table->date('tanggal');
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['selesai', 'batal'])->default('selesai');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['toko_id', 'tanggal']);
            $table->index(['kode_transaksi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
