@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-breadcrumb', 'User / Edit')

@section('content')
<div class="form-container">
    <div class="form-header">
        <a href="{{ route('users.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            <span>Kembali</span>
        </a>
        <div class="form-header-info">
            <h2 class="form-title">Update Data User</h2>
            <p class="form-subtitle">Perbarui profil atau role pengguna: <strong>{{ $user->name }}</strong></p>
        </div>
    </div>

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="note-form">
        @csrf
        @method('PUT')
        
        {{-- Nama Lengkap --}}
        <div class="form-group">
            <label for="name" class="form-label">
                <span class="label-icon">👤</span>
                Nama Lengkap
                <span class="required">*</span>
            </label>
            <input type="text" name="name" id="name" class="form-input @error('name') is-error @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Alamat Email --}}
        <div class="form-group">
            <label for="email" class="form-label">
                <span class="label-icon">📧</span>
                Alamat Email
                <span class="required">*</span>
            </label>
            <input type="email" name="email" id="email" class="form-input @error('email') is-error @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Role / Peran --}}
        <div class="form-group">
            <label for="role" class="form-label">
                <span class="label-icon">🛡️</span>
                Role / Hak Akses
                <span class="required">*</span>
            </label>
            {{-- [LOGIKA] Hanya admin yang bisa mengubah role orang lain --}}
            <select name="role" id="role" class="form-input @error('role') is-error @enderror" required>
                <option value="member" {{ old('role', $user->role) == 'member' ? 'selected' : '' }}>Member (Standard)</option>
                <option value="leader" {{ old('role', $user->role) == 'leader' ? 'selected' : '' }}>Leader</option>
                <option value="administrator" {{ old('role', $user->role) == 'administrator' ? 'selected' : '' }}>Administrator (Full Access)</option>
            </select>
            @error('role')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Nama Tim --}}
        <div class="form-group">
            <label for="team" class="form-label">
                <span class="label-icon">🏢</span>
                Nama Tim (Opsional)
            </label>
            <input type="text" name="team" id="team" class="form-input @error('team') is-error @enderror" value="{{ old('team', $user->team) }}" placeholder="Contoh: Unit IT, Akuntansi, dsb.">
            @error('team')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Update Password (Optional) --}}
        <div style="margin-top: 10px; padding: 20px; background: rgba(148, 180, 193, 0.05); border-radius: var(--radius-md); border: 1px dashed var(--color-border);">
            <h4 style="font-size: 0.9rem; margin-bottom: 12px; color: var(--color-primary);">Ganti Password (Kosongkan jika tidak ingin merubah)</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-input @error('password') is-error @enderror" placeholder="Minimal 8 karakter">
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Ulangi password">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('users.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-primary">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
