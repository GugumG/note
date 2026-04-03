@extends('layouts.app')

@section('title', 'Selesaikan Task')
@section('page-title', 'Validasi Penyelesaian Task')
@section('page-breadcrumb', 'NoteApp / Tasks / Selesaikan')

@section('content')
    <div class="form-container">
        <div class="form-header">
            <a href="{{ route('notes.index') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Kembali
            </a>
            <div class="form-header-info">
                <h2 class="form-title">Selesaikan: {{ $task->title }}</h2>
                <p class="form-subtitle">Tunjukkan bukti keberhasilanmu</p>
            </div>
        </div>

        <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="note-form">
            @csrf

            <div class="form-group" style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid var(--color-border); margin-bottom: 24px;">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--color-text-secondary);">
                    Selamat! Anda telah sampai di tahap penyelesaian task. Silakan masukkan link bukti (gambar/doc) atau tuliskan catatan ringkas mengenai apa yang Anda capai di task ini.
                </p>
            </div>

            <div class="form-group">
                <label for="completion_data" class="form-label">
                    <span class="label-icon">🏆</span>
                    Input Bukti Penyelesaian (Link atau Catatan) <span class="required">*</span>
                </label>
                <textarea name="completion_data" id="completion_data" class="form-input @error('completion_data') is-error @enderror" rows="6" required placeholder="Paste link di sini atau tuliskan catatan penyelesaian..."></textarea>
                @error('completion_data') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('notes.index') }}" class="btn-back">Batal</a>
                <button type="submit" class="btn-primary" style="background: var(--color-success); border: none;">
                    🎯 Simpan & Selesaikan
                </button>
            </div>
        </form>
    </div>
@endsection
