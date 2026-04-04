@extends('layouts.app')

@section('title', 'Edit Task')
@section('page-title', 'Edit Task')
@section('page-breadcrumb', 'NoteApp / Tasks / Edit')

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
                <h2 class="form-title">Edit Task</h2>
                <p class="form-subtitle">Perbarui jadwal kegiatanmu</p>
            </div>
        </div>

        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="note-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title" class="form-label">
                    <span class="label-icon">📌</span>
                    Judul Task <span class="required">*</span>
                </label>
                <input type="text" name="title" id="title" class="form-input @error('title') is-error @enderror" value="{{ old('title', $task->title) }}" required>
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group-row" style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="category" class="form-label">
                        <span class="label-icon">📂</span>
                        Proyek / Kategori
                    </label>
                    <input type="text" name="category" id="category" class="form-input @error('category') is-error @enderror" value="{{ old('category', $task->category) }}" placeholder="Contoh: Projek A, Belajar...">
                    @error('category') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="color" class="form-label">
                        <span class="label-icon">🎨</span>
                        Warna Label
                    </label>
                    <input type="color" name="color" id="color" class="form-input @error('color') is-error @enderror" value="{{ old('color', $task->color ?? '#547792') }}" style="height: 45px; cursor: pointer; padding: 4px;">
                    @error('color') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="deadline" class="form-label">
                    <span class="label-icon">📅</span>
                    Deadline
                </label>
                <input type="date" name="deadline" id="deadline" class="form-input @error('deadline') is-error @enderror" value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d') : '') }}">
                @error('deadline') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">
                    <span class="label-icon">📊</span>
                    Status <span class="required">*</span>
                </label>
                <select name="status" id="status" class="form-input @error('status') is-error @enderror" required>
                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="suspend" {{ old('status', $task->status) == 'suspend' ? 'selected' : '' }}>Suspend</option>
                    <option value="on progress" {{ old('status', $task->status) == 'on progress' ? 'selected' : '' }}>On Progress</option>
                    <option value="complete" {{ old('status', $task->status) == 'complete' ? 'selected' : '' }}>Complete</option>
                </select>
                @error('status') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="content" class="form-label">
                    <span class="label-icon">📝</span>
                    Isi Task
                </label>
                <textarea name="content" id="content" class="form-input @error('content') is-error @enderror" rows="5">{{ old('content', $task->content) }}</textarea>
                @error('content') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('notes.index') }}" class="btn-back">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                        <path d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                    </svg>
                    Update Task
                </button>
            </div>
        </form>
    </div>
@endsection
