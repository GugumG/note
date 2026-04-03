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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dateTime('completed_at')->nullable()->after('status');
            $table->integer('days_diff')->nullable()->after('completed_at');
            $table->boolean('is_pinned')->default(false)->after('content');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['completed_at', 'days_diff', 'is_pinned']);
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('is_pinned');
        });
    }
};
