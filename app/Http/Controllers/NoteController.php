<?php

// ============================================================
// FILE: app/Http/Controllers/NoteController.php
// STEP [3] — Controller utama yang menangani semua request CRUD.
//            Semua data kini di-scope berdasarkan User yang sedang login.
// ============================================================

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Task;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NoteController extends Controller
{
    /**
     * Tampilkan semua notes milik user yang sedang login.
     */
    public function index(Request $request)
    {
        // Ambil catatan milik user, urutkan: Pin -> Tanggal Terbaru
        $query = auth()->user()->notes()
                    ->orderBy('is_pinned', 'desc')
                    ->orderBy('note_date', 'desc');

        if ($request->has('hashtag') && $request->hashtag != '') {
            $searchTerm = $request->hashtag;
            $query->where('hashtags', 'like', '%' . $searchTerm . '%');
        }

        $notes = $query->get();

        // Ambil task milik user untuk sidebar
        $tasks = auth()->user()->tasks()
                    ->where('status', '!=', 'complete')
                    ->orderBy('is_pinned', 'desc')
                    ->orderByRaw("CASE 
                        WHEN deadline IS NOT NULL AND deadline < date('now', 'localtime') THEN 2
                        WHEN deadline IS NOT NULL AND deadline <= date('now', 'localtime', '+3 days') THEN 1
                        ELSE 0
                    END DESC")
                    ->orderBy('deadline', 'asc')
                    ->get();

        return view('notes.index', compact('notes', 'tasks'));
    }

    /**
     * Toggle status pin untuk catatan.
     */
    public function togglePin(Note $note)
    {
        if ($note->user_id !== auth()->id()) abort(403);
        
        $note->update(['is_pinned' => !$note->is_pinned]);

        return redirect()->back()->with('success', $note->is_pinned ? 'Catatan disematkan!' : 'Pin dilepas!');
    }

    /**
     * Tampilkan detail catatan.
     */
    public function show(Note $note)
    {
        if ($note->user_id !== auth()->id()) abort(403);
        return view('notes.show', compact('note'));
    }

    /**
     * Tampilkan form pembuatan catatan.
     */
    public function create()
    {
        $today = now()->format('Y-m-d');
        return view('notes.create', compact('today'));
    }

    /**
     * Simpan catatan baru terikat ke User.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'note_date' => 'required|date',
            'content'   => 'required|string',
            'hashtags'  => 'nullable|string|max:255',
        ]);

        auth()->user()->notes()->create($validated);

        return redirect()->route('notes.index')->with('success', 'Catatan berhasil dibuat!');
    }

    /**
     * Tampilkan form edit catatan.
     */
    public function edit(Note $note)
    {
        if ($note->user_id !== auth()->id()) abort(403);
        return view('notes.edit', compact('note'));
    }

    /**
     * Update catatan.
     */
    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'note_date' => 'required|date',
            'content'   => 'required|string',
            'hashtags'  => 'nullable|string|max:255',
        ]);

        if ($note->user_id !== auth()->id()) abort(403);
        $note->update($validated);

        return redirect()->route('notes.index')->with('success', 'Catatan berhasil diperbarui!');
    }

    /**
     * Hapus catatan.
     */
    public function destroy(Note $note)
    {
        if ($note->user_id !== auth()->id()) abort(403);
        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Catatan berhasil dihapus!');
    }

    /**
     * Handle upload gambar dari Quill.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $path = $request->file('image')->store('note-images', 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Ekspor catatan ke PDF.
     */
    public function exportPdf($id)
    {
        $note = auth()->user()->notes()->findOrFail($id);
        $pdf = Pdf::loadView('notes.pdf', compact('note'));

        $filename = 'Catatan_' . str_replace([' ', '/', '\\'], '_', $note->title) . '.pdf';

        return $pdf->download($filename);
    }
}
