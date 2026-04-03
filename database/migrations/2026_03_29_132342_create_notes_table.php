<?php

// ============================================================
// FILE: database/migrations/..._create_notes_table.php
// STEP [1] — Mendefinisikan struktur tabel "notes" di database.
//            Tabel ini digunakan oleh Model Note (STEP [2])
//            dan diisi/diakses oleh NoteController (STEP [3]).
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * [1a] Jalankan migrasi: buat tabel "notes" dengan kolom-kolomnya.
     */
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();                          // [1b] Primary key auto-increment
            $table->string('title');               // [1c] Kolom judul catatan
            $table->date('note_date');             // [1d] Kolom tanggal catatan (isi otomatis dari form)
            $table->longText('content');           // [1e] Kolom isi catatan (HTML dari Quill RTE)
            $table->timestamps();                  // [1f] created_at & updated_at otomatis Laravel
        });
    }

    /**
     * [1g] Rollback migrasi: hapus tabel "notes".
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
