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
        Schema::table('kategoris', function (Blueprint $table) {
            $table->index('toko_id', 'kategoris_toko_id_index');
        });
        Schema::table('stock_out', function (Blueprint $table) {
            $table->index('toko_id', 'stock_out_toko_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropIndex('kategoris_toko_id_index');
        });
        Schema::table('stock_out', function (Blueprint $table) {
            $table->dropIndex('stock_out_toko_id_index');
        });
    }
};
