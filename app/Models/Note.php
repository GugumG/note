<?php

// ============================================================
// FILE: app/Models/Note.php
// STEP [2] — Model Eloquent untuk tabel "notes".
//            Digunakan oleh NoteController (STEP [3]) untuk
//            operasi CRUD (Create, Read, Update, Delete).
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    // [2a] $fillable: Kolom-kolom yang boleh diisi secara mass-assignment
    //      (misal: Note::create($request->all())).
    //      Kolom ini sesuai dengan field di form (STEP [5]) dan migration (STEP [1]).
    protected $fillable = [
        'title',      // [2b] Judul catatan → lihat form di notes/create.blade.php (STEP [5b])
        'note_date',  // [2c] Tanggal catatan → lihat form di notes/create.blade.php (STEP [5c])
        'content',    // [2d] Isi catatan (HTML dari Quill) → lihat form di notes/create.blade.php (STEP [5d])
        'is_pinned',
        'hashtags',
    ];

    // [2e] $casts: Cast kolom ke tipe data PHP yang sesuai
    protected $casts = [
        'note_date' => 'date', // [2f] note_date di-cast ke objek Carbon\Carbon
        'is_pinned' => 'boolean',
    ];
}
