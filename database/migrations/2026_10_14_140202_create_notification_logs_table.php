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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toko_id')->constrained('tokos')->cascadeOnDelete();
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->nullOnDelete();
            $table->string('type', 50);         // 'critical_stock' | 'daily_digest'
            $table->string('channel', 20);      // 'email' (extensible ke 'whatsapp' nanti)
            $table->string('status', 20)->default('sent'); // 'sent' | 'failed' | 'skipped'
            $table->string('recipient')->nullable();       // email tujuan
            $table->json('payload')->nullable();           // isi notifikasi (untuk debugging)
            $table->text('error')->nullable();             // pesan error jika status = 'failed'
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['toko_id', 'type', 'created_at']);
            $table->index(['barang_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
