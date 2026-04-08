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
    // [2a] $fillable: Kolom-kolom yang boleh diisi secara mass-assignment.
    protected $fillable = [
        'user_id',
        'title',
        'note_date',
        'content',
        'is_pinned',
        'hashtags',
        'visibility', // [AI Rules] Menambahkan visibility (Personal/Team)
    ];

    // [2e] $casts: Cast kolom ke tipe data PHP yang sesuai.
    protected $casts = [
        'note_date' => 'date',
        'is_pinned' => 'boolean',
    ];

    /**
     * Relasi ke User: Setiap catatan dimiliki oleh satu user.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
