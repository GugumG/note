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
     * Tampilkan semua notes dengan logic filter (Personal vs Shared).
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $filter = $request->get('filter', 'all'); // all, mine, shared
        
        // Base Query: Catatan milik sendiri
        $myNotesQuery = $user->notes();
        
        // Query Catatan Shared: Milik orang lain di tim yang sama dengan visibilitas 'team'
        $sharedNotesQuery = Note::where('user_id', '!=', $user->id)
            ->where('visibility', 'team')
            ->whereHas('user', function($q) use ($user) {
                $q->where('team', $user->team)->whereNotNull('team');
            });

        // Terapkan Filter
        if ($filter === 'mine') {
            $query = $myNotesQuery;
        } elseif ($filter === 'shared') {
            $query = $sharedNotesQuery;
        } else {
            // Gabungkan catatan sendiri dan catatan tim (all)
            $query = Note::where(function($q) use ($user) {
                // Milik sendiri
                $q->where('user_id', $user->id)
                  // ATAU milik tim (orang lain se-tim)
                  ->orWhere(function($sq) use ($user) {
                      $sq->where('user_id', '!=', $user->id)
                         ->where('visibility', 'team')
                         ->whereHas('user', function($tq) use ($user) {
                             $tq->where('team', $user->team)->whereNotNull('team');
                         });
                  });
            });
        }

        // Urutkan: Pin -> Tanggal Terbaru
        $notes = $query->orderBy('is_pinned', 'desc')
                      ->orderBy('note_date', 'desc')
                      ->get();

        // Ambil task milik user (termasuk yang di-invite) untuk sidebar
        $tasks = Task::where(function($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->orWhere('assigned_user_id', $user->id);
                    })
                    ->where('status', '!=', 'complete')
                    ->orderBy('is_pinned', 'desc')
                    ->orderBy('deadline', 'asc')
                    ->get();

        return view('notes.index', compact('notes', 'tasks', 'filter'));
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
        // Cek Otoritas Otoritas: Milik sendiri ATAU satu tim & visibility team
        $user = auth()->user();
        $isOwner = $note->user_id === $user->id;
        $isTeamMember = $note->visibility === 'team' && $note->user->team === $user->team && $user->team !== null;

        if (!$isOwner && !$isTeamMember) abort(403);
        
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
            'title'      => 'required|string|max:255',
            'note_date'  => 'required|date',
            'content'    => 'required|string',
            'hashtags'   => 'nullable|string|max:255',
            'visibility' => 'required|in:personal,team',
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
            'title'      => 'required|string|max:255',
            'note_date'  => 'required|date',
            'content'    => 'required|string',
            'hashtags'   => 'nullable|string|max:255',
            'visibility' => 'required|in:personal,team',
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
