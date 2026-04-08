@extends('layouts.app')

@section('title', 'Tambah Task')
@section('page-title', 'Tambah Task Baru')
@section('page-breadcrumb', 'NoteApp / Tasks / Tambah')

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
                <h2 class="form-title">Task Baru</h2>
                <p class="form-subtitle">Jadwalkan kegiatan barumu</p>
            </div>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST" class="note-form" id="create-task-form">
            @csrf

            {{-- Main Form Card --}}
            <div style="background: white; border-radius: var(--radius-lg); padding: 30px; box-shadow: var(--shadow-md); border: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 24px;">
                
                {{-- Row 1: Judul --}}
                <div class="form-group">
                    <label for="title" class="form-label">
                        <span class="label-icon">📌</span>
                        Judul Task <span class="required">*</span>
                    </label>
                    <input type="text" name="title" id="title" class="form-input @error('title') is-error @enderror" value="{{ old('title') }}" required placeholder="Apa yang ingin dikerjakan?">
                    @error('title') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Row 2: Proyek & Warna --}}
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="category" class="form-label">
                            <span class="label-icon">📂</span>
                            Proyek / Kategori
                        </label>
                        <input type="text" name="category" id="category" class="form-input @error('category') is-error @enderror" value="{{ old('category') }}" placeholder="Contoh: Projek A, Belajar...">
                        @error('category') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="color" class="form-label">
                            <span class="label-icon">🎨</span>
                            Warna Label
                        </label>
                        <input type="color" name="color" id="color" class="form-input @error('color') is-error @enderror" value="{{ old('color', '#547792') }}" style="height: 45px; cursor: pointer; padding: 5px;">
                        @error('color') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Row 3: Deadline & Status --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="deadline" class="form-label">
                            <span class="label-icon">📅</span>
                            Deadline
                        </label>
                        <input type="date" name="deadline" id="deadline" class="form-input @error('deadline') is-error @enderror" value="{{ old('deadline') }}">
                        @error('deadline') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="status" class="form-label">
                            <span class="label-icon">📊</span>
                            Status Awal <span class="required">*</span>
                        </label>
                        <select name="status" id="status" class="form-input @error('status') is-error @enderror" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="on progress" {{ old('status') == 'on progress' ? 'selected' : '' }}>On Progress</option>
                            <option value="suspend" {{ old('status') == 'suspend' ? 'selected' : '' }}>Suspend</option>
                        </select>
                        @error('status') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Row 4: Invite User (Hanya jika tersedia user lain di tim) --}}
                @if(count($users) > 0)
                <div style="padding: 20px; background: rgba(148, 180, 193, 0.05); border-radius: var(--radius-md); border: 1px dashed var(--color-secondary);">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="assigned_user_id" class="form-label">
                            <span class="label-icon">📧</span>
                            Invite User (Pelaksana Tugas)
                        </label>
                        <select name="assigned_user_id" id="assigned_user_id" class="form-input @error('assigned_user_id') is-error @enderror">
                            <option value="">-- Pilih Rekan Tim (Opsional) --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('assigned_user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="form-subtitle" style="font-size: 0.75rem; margin-top: 8px; color: var(--color-primary);">Rekan yang di-invite dapat melihat kemajuan tugas ini dan merubah statusnya.</p>
                        @error('assigned_user_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                @endif

                {{-- Row 5: Deskripsi --}}
                <div class="form-group">
                    <label for="content" class="form-label">
                        <span class="label-icon">📝</span>
                        Detail / Isi Task
                    </label>
                    <textarea name="content" id="content" class="form-input @error('content') is-error @enderror" rows="6" placeholder="Berikan instruksi atau detail tugas di sini...">{{ old('content') }}</textarea>
                    @error('content') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Buttons --}}
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px; padding-top: 20px; border-top: 1px solid var(--color-border);">
                    <a href="{{ route('notes.index') }}" class="btn-back" style="text-decoration: none; border: 1px solid var(--color-border); color: var(--color-text-muted);">Batal</a>
                    <button type="submit" class="btn-primary" style="min-width: 150px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                            <path d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                        </svg>
                        Simpan Task
                    </button>
                </div>
            </div>
        </form>
        </form>
    </div>
@endsection
