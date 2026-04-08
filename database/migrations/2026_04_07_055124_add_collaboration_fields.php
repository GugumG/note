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
        // 1. Menambahkan kolom 'team' ke tabel users agar Admin bisa mengelompokkan pengguna.
        Schema::table('users', function (Blueprint $table) {
            $table->string('team')->nullable()->after('role');
        });

        // 2. Menambahkan kolom 'visibility' ke tabel notes (Personal vs Team).
        Schema::table('notes', function (Blueprint $table) {
            $table->enum('visibility', ['personal', 'team'])->default('personal')->after('hashtags');
        });

        // 3. Menambahkan kolom kolaborasi ke tabel tasks (Invite / Penugasan).
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete()->after('user_id');
            $table->boolean('is_approved')->default(false)->after('status');
        });

        // 4. Inisialisasi data awal: Masukkan akun utama ke tim yang sama ("Unit IT").
        // [AI Rules] Integritas Data: Menjamin data lama tidak terhapus.
        \DB::table('users')
            ->whereIn('email', ['ggumilar919@gmail.com', 'rifaearly2k@gmail.com'])
            ->update(['team' => 'Unit IT']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('team');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_user_id');
            $table->dropColumn('is_approved');
        });
    }
};
