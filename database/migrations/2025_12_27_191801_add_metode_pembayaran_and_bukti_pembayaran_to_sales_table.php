<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('metode_pembayaran')
                  ->after('total');

            $table->string('bukti_pembayaran')
                  ->nullable()
                  ->after('metode_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'metode_pembayaran',
                'bukti_pembayaran',
            ]);
        });
    }
};