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
        Schema::table('tokos', function (Blueprint $table) {
            $table->boolean('notif_email_enabled')->default(true)->after('phone')
                ->comment('Toggle aktif/nonaktif notifikasi email');
            $table->time('notif_digest_time')->default('08:00:00')->after('notif_email_enabled')
                ->comment('Jam pengiriman laporan stok harian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tokos', function (Blueprint $table) {
            $table->dropColumn(['notif_email_enabled', 'notif_digest_time']);
        });
    }
};
