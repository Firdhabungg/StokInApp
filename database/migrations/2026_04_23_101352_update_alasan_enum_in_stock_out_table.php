<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE stock_out MODIFY COLUMN alasan ENUM('penjualan','rusak','kadaluarsa','retur','hilang','sample','lainnya') NOT NULL DEFAULT 'penjualan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE stock_out MODIFY COLUMN alasan ENUM('penjualan','rusak','kadaluarsa','retur','lainnya') NOT NULL DEFAULT 'penjualan'");
    }
};
