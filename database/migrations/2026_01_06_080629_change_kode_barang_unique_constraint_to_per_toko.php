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
            // Kode barang harus unik per toko
            $table->dropUnique('barangs_kode_barang_unique');
            
            // Tambahkan unique constraint (toko_id + kode_barang)
            $table->unique(['toko_id', 'kode_barang'], 'barangs_toko_kode_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Hapus unique constraint (toko_id + kode_barang)
            $table->dropUnique('barangs_toko_kode_unique');
            
            // Restore old unique constraint
            $table->unique('kode_barang', 'barangs_kode_barang_unique');
        });
    }
};
