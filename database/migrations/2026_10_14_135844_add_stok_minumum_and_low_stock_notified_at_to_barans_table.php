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
        Schema::table('barangs', function (Blueprint $table) {
            $table->unsignedInteger('stok_minimum')->default(10)->after('stok')
                ->comment('Ambang batas stok minimum per barang');
            $table->timestamp('low_stock_notified_at')->nullable()->after('stok_minimum')
                ->comment('Cooldown: kapan terakhir notifikasi stok dikirim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['stok_minimum', 'low_stock_notified_at']);
        });
    }
};
