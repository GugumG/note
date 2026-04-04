<?php

// ============================================================
// FILE: app/Http/Controllers/NoteController.php
// STEP [3] — Controller utama yang menangani semua request CRUD.
//            Routes dideklarasikan di web.php (STEP [4]).
//            Controller ini menghubungkan Model Note (STEP [2])
//            dengan Views di resources/views/notes/ (STEP [5],[6],[7]).
// ============================================================

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NoteController extends Controller
{
    // --------------------------------------------------------
    // [3b] INDEX — Tampilkan semua notes dalam format card grid.
    //      Route: GET /notes → lihat web.php (STEP [4])
    //      View : resources/views/notes/index.blade.php (STEP [5])
    // --------------------------------------------------------
    public function index(Request $request)
    {
        // [3c] Ambil semua catatan dari DB, urutkan PINNED dlu baru tanggal terbaru
        $query = Note::orderBy('is_pinned', 'desc')
                    ->orderBy('note_date', 'desc');

        // [New] Filter berdasarkan hashtag jika ada input pencarian
        if ($request->has('hashtag') && $request->hashtag != '') {
            $searchTerm = $request->hashtag;
            // Jika input tidak diawali #, tambahkan otomatis untuk pencarian (opsional, tapi user biasanya input #)
            $query->where('hashtags', 'like', '%' . $searchTerm . '%');
        }

        $notes = $query->get();

        // [New] Ambil semua task yang statusnya BUKAN 'complete' untuk sidebar
        //      Urutkan: Manual Pin > Telat > Mepet > Deadline terdekat
        $tasks = \App\Models\Task::where('status', '!=', 'complete')
                                 ->orderBy('is_pinned', 'desc')
                                 ->orderByRaw("CASE 
                                     WHEN deadline IS NOT NULL AND deadline < date('now', 'localtime') THEN 2
                                     WHEN deadline IS NOT NULL AND deadline <= date('now', 'localtime', '+3 days') THEN 1
                                     ELSE 0
                                 END DESC")
                                 ->orderBy('deadline', 'asc')
                                 ->get();

        // [3d] Kirim data $notes dan $tasks ke view index
        return view('notes.index', compact('notes', 'tasks'));
    }

    /**
     * Toggle the pinned status of the note.
     */
    public function togglePin(Note $note)
    {
        $note->update([
            'is_pinned' => !$note->is_pinned,
        ]);

        return redirect()->back()->with('success', $note->is_pinned ? 'Catatan disematkan!' : 'Pin dilepas!');
    }

    // --------------------------------------------------------
    // [3bb] SHOW — Tampilkan detail satu catatan secara penuh.
    //       Route: GET /notes/{note} → lihat web.php (STEP [4c])
    //       View : resources/views/notes/show.blade.php (STEP [10])
    // --------------------------------------------------------
    public function show(Note $note)
    {
        // [3bc] Laravel otomatis temukan catatan berdasarkan ID (Route Model Binding)
        //       Kirim $note ke view show (STEP [10])
        return view('notes.show', compact('note'));
    }

    // --------------------------------------------------------
    // [3e] CREATE — Tampilkan form untuk membuat catatan baru.
    //      Route: GET /notes/create → lihat web.php (STEP [4])
    //      View : resources/views/notes/create.blade.php (STEP [6])
    // --------------------------------------------------------
    public function create()
    {
        // [3f] Kirim tanggal hari ini ke view agar field date terisi otomatis
        $today = now()->format('Y-m-d');
        return view('notes.create', compact('today'));
    }

    // --------------------------------------------------------
    // [3g] STORE — Simpan catatan baru ke database.
    //      Route: POST /notes → lihat web.php (STEP [4])
    //      Setelah berhasil, redirect ke halaman index (STEP [3b])
    // --------------------------------------------------------
    public function store(Request $request)
    {
        // [3h] Validasi input dari form (STEP [6])
        $validated = $request->validate([
            'title'     => 'required|string|max:255', // [3i] Judul wajib diisi
            'note_date' => 'required|date',           // [3j] Tanggal wajib diisi
            'content'   => 'required|string',         // [3k] Isi catatan wajib diisi
            'hashtags'  => 'nullable|string|max:255', // [New] Hashtag opsional
        ]);

        // [3l] Simpan ke DB menggunakan Model Note (STEP [2])
        Note::create($validated);

        // [3m] Redirect ke halaman list view dengan pesan sukses
        return redirect()->route('notes.index')
                         ->with('success', 'Catatan berhasil dibuat!');
    }

    // --------------------------------------------------------
    // [3n] EDIT — Tampilkan form edit untuk catatan yang sudah ada.
    //      Route: GET /notes/{note}/edit → lihat web.php (STEP [4])
    //      View : resources/views/notes/edit.blade.php (STEP [7])
    // --------------------------------------------------------
    public function edit(Note $note)
    {
        // [3o] Laravel otomatis temukan catatan berdasarkan ID (Route Model Binding)
        return view('notes.edit', compact('note'));
    }

    // --------------------------------------------------------
    // [3p] UPDATE — Update catatan yang sudah ada di database.
    //      Route: PUT /notes/{note} → lihat web.php (STEP [4])
    //      Setelah berhasil, redirect ke halaman index (STEP [3b])
    // --------------------------------------------------------
    public function update(Request $request, Note $note)
    {
        // [3q] Validasi input dari form edit (STEP [7])
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'note_date' => 'required|date',
            'content'   => 'required|string',
            'hashtags'  => 'nullable|string|max:255', // [New] Hashtag opsional
        ]);

        // [3r] Update data di DB menggunakan Model Note (STEP [2])
        $note->update($validated);

        // [3s] Redirect ke list view dengan pesan sukses
        return redirect()->route('notes.index')
                         ->with('success', 'Catatan berhasil diperbarui!');
    }

    // --------------------------------------------------------
    // [3t] DESTROY — Hapus catatan dari database.
    //      Route: DELETE /notes/{note} → lihat web.php (STEP [4])
    //      Setelah berhasil, redirect ke halaman index (STEP [3b])
    // --------------------------------------------------------
    public function destroy(Note $note)
    {
        // [3u] Hapus catatan dari DB menggunakan Model Note (STEP [2])
        $note->delete();

        // [3v] Redirect ke list view dengan pesan sukses
        return redirect()->route('notes.index')
                         ->with('success', 'Catatan berhasil dihapus!');
    }

    // --------------------------------------------------------
    // [3w] UPLOAD IMAGE — Handle upload gambar dari Quill editor.
    //      Route: POST /notes/upload-image → lihat web.php (STEP [4e])
    //      Dipanggil via AJAX dari JavaScript di form views (STEP [6],[7])
    // --------------------------------------------------------
    public function uploadImage(Request $request)
    {
        // [3x] Validasi bahwa request mengandung file gambar
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // max 5MB
        ]);

        // [3y] Simpan file ke folder public/storage/note-images
        $path = $request->file('image')->store('note-images', 'public');

        // [3z] Kembalikan URL gambar dalam format JSON ke JavaScript (STEP [6],[7])
        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Export a specific note to PDF.
     */
    public function exportPdf($id)
    {
        $note = Note::findOrFail($id);

        // Load the specialized PDF view
        $pdf = Pdf::loadView('notes.pdf', compact('note'));

        // Sanitize title for filename
        $filename = 'Catatan_' . str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $note->title) . '.pdf';

        return $pdf->download($filename);
    }
}
