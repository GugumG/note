<?php

// ============================================================
// FILE: routes/web.php
// STEP [4] — Definisi semua URL routes aplikasi.
//            Setiap route diarahkan ke NoteController (STEP [3]).
//            Routes ini dipanggil dari View Blade (STEP [5],[6],[7]).
// ============================================================

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController; // [4a] Import NoteController (STEP [3])

// [4b] Redirect root URL "/" ke halaman daftar catatan
Route::get('/', function () {
    return redirect()->route('notes.index');
});

// [4c] Resource routes untuk Note CRUD
//      Ini menghasilkan 7 routes lengkap:
//      GET    /notes              → NoteController@index   (STEP [3b])  → View: notes.index  (STEP [5])
//      GET    /notes/create       → NoteController@create  (STEP [3e])  → View: notes.create (STEP [6])
//      POST   /notes              → NoteController@store   (STEP [3g])
//      GET    /notes/{note}       → NoteController@show    (STEP [3bb]) → View: notes.show   (STEP [10])
//      GET    /notes/{note}/edit  → NoteController@edit    (STEP [3n])  → View: notes.edit   (STEP [7])
//      PUT    /notes/{note}       → NoteController@update  (STEP [3p])
//      DELETE /notes/{note}       → NoteController@destroy (STEP [3t])
Route::resource('notes', NoteController::class);

// [New] Resource routes for Task CRUD
use App\Http\Controllers\TaskController;
Route::resource('tasks', TaskController::class);

// [New] Special routes for completing a task with validation data
Route::get('/tasks/{task}/complete', [TaskController::class, 'completeForm'])->name('tasks.complete-form');
Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

// [New] Toggle pin status
Route::post('/tasks/{task}/toggle-pin', [TaskController::class, 'togglePin'])->name('tasks.toggle-pin');
Route::post('/notes/{note}/toggle-pin', [NoteController::class, 'togglePin'])->name('notes.toggle-pin');

// [4e] Route khusus untuk upload gambar dari Quill editor
//      Dipanggil dari JavaScript di create.blade.php (STEP [6]) dan edit.blade.php (STEP [7])
Route::post('/notes/upload-image', [NoteController::class, 'uploadImage'])
     ->name('notes.upload-image');
