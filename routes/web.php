<?php

// ============================================================
// FILE: routes/web.php
// STEP [4] — Definisi semua URL routes aplikasi.
//            Setiap route diarahkan ke NoteController (STEP [3]).
//            Routes ini dipanggil dari View Blade (STEP [5],[6],[7]).
// ============================================================

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SettingController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// --------------------------------------------------------
// [Auth] — Routes untuk Autentikasi (Guest Only)
// --------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --------------------------------------------------------
// [Protected] — Semua route di bawah ini wajib Login (Auth Middleare)
// --------------------------------------------------------
Route::middleware('auth')->group(function () {

    // [4b] Redirect root URL "/" ke halaman daftar catatan
    Route::get('/', function () {
        return redirect()->route('notes.index');
    });

    // [4c] Resource routes untuk Note CRUD
    Route::resource('notes', NoteController::class);
    Route::get('/notes/{note}/export-pdf', [NoteController::class, 'exportPdf'])->name('notes.export-pdf');

    // [New] Resource routes for Task CRUD
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('tasks/{task}/approve', [TaskController::class, 'approve'])->name('tasks.approve');
    Route::post('tasks/{task}/toggle-pin', [TaskController::class, 'togglePin'])->name('tasks.toggle-pin');
    Route::post('notes/{note}/toggle-pin', [NoteController::class, 'togglePin'])->name('notes.toggle-pin');

    // [New] Pengaturan / Theme Routes
    Route::get('/settings/theme', [SettingController::class, 'theme'])->name('settings.theme');
    Route::post('/settings/theme', [SettingController::class, 'updateTheme'])->name('settings.update-theme');

    // [New] User Management CRUD (Akses dibatasi di Controller)
    Route::resource('users', UserController::class);
    // Alias untuk kompatibilitas dengan view theme yang sudah ada
    Route::get('/settings/users', [UserController::class, 'index'])->name('settings.users');
    
    Route::get('/settings/roles', function() { return view('settings.dummy', ['title' => 'Role Management']); })->name('settings.roles');

    // [New] Special routes for completing a task with validation data
    Route::get('/tasks/{task}/complete', [TaskController::class, 'completeForm'])->name('tasks.complete-form');

    // [4e] Route khusus untuk upload gambar dari Quill editor
    Route::post('/notes/upload-image', [NoteController::class, 'uploadImage'])
         ->name('notes.upload-image');
});
