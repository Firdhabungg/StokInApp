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
        // Add toko_id to barangs table
        Schema::table('barangs', function (Blueprint $table) {
            $table->foreignId('toko_id')->after('id')->constrained('tokos')->onDelete('cascade');
        });

        // Add toko_id to kategoris table
        Schema::table('kategoris', function (Blueprint $table) {
            $table->foreignId('toko_id')->after('kategori_id')->constrained('tokos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
        });

        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
        });
    }
};
