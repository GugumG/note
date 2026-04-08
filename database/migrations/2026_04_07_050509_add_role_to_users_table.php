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
        Schema::table('users', function (Blueprint $table) {
            // Menambah kolom role dengan default 'member' setelah kolom email.
            // [AI Rules] KISS & Integritas Data: Menambahkan kolom tanpa menghapus data lama.
            $table->string('role')->default('member')->after('email');
        });

        // Set akun khusus menjadi administrator berdasarkan instruksi USER.
        // Data lama tetap aman karena kita hanya mengupdate satu kolom.
        \DB::table('users')
            ->where('email', 'ggumilar919@gmail.com')
            ->update(['role' => 'administrator']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom role jika migration di-rollback.
            $table->dropColumn('role');
        });
    }
};
