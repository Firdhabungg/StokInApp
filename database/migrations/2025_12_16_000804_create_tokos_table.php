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
        Schema::create('tokos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->text('address');
            $table->string('phone', 20);
            $table->timestamps();
        });

        // Add toko_id and role to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('toko_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->enum('role', ['owner', 'admin', 'staff'])->default('staff')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn(['toko_id', 'role']);
        });

        Schema::dropIfExists('tokos');
    }
};
