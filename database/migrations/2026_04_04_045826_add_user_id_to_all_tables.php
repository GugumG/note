<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * STEP [User-1] — Menambahkan kolom user_id ke tabel notes, tasks, dan settings.
     *                 Ini memungkinkan pemisahan data antar pengguna (Multi-user).
     */
    public function up(): void
    {
        // 1. NOTES: Tambah user_id
        Schema::table('notes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // 2. TASKS: Tambah user_id
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // 3. SETTINGS: Tambah user_id dan ubah unique constraint
        Schema::table('settings', function (Blueprint $table) {
            // Hapus unique constraint lama pada 'key' agar bisa duplikat untuk user berbeda
            $table->dropUnique('settings_key_unique');
            
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            
            // Tambah unique constraint baru: kombinasi user_id + key
            $table->unique(['user_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'key']);
            $table->dropConstrainedForeignId('user_id');
            $table->unique('key');
        });
    }
};
