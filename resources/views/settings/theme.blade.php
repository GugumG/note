@extends('layouts.app')

@section('title', 'Pengaturan Tema')
@section('page-title', 'Pengaturan Tema')
@section('page-breadcrumb', 'Aplikasi / Pengaturan')

@section('content')
<div class="settings-container" style="max-width: 800px; margin: 0 auto;">
    
    {{-- Info Header --}}
    <div class="note-card" style="margin-bottom: 24px; border-left: 4px solid var(--color-secondary);">
        <h3 style="font-size: 1.1rem; margin-bottom: 8px;">🎨 Kustomisasi Tampilan</h3>
        <p style="font-size: 0.9rem; color: var(--color-text-muted); line-height: 1.5;">
            Sesuaikan skema warna aplikasi NoteApp agar sesuai dengan identitas kantor atau selera Anda. 
            Perubahan akan langsung diterapkan ke seluruh elemen aplikasi setelah disimpan.
        </p>
    </div>

    {{-- Sub-Navigation Dummies --}}
    <div style="display: flex; gap: 12px; margin-bottom: 24px;">
        <a href="{{ route('settings.theme') }}" class="btn-primary" style="font-size: 0.85rem; padding: 8px 16px;">Tampilan</a>
        <a href="{{ route('settings.users') }}" class="btn-back" style="font-size: 0.85rem; padding: 8px 16px; margin: 0;">User Management</a>
        <a href="{{ route('settings.roles') }}" class="btn-back" style="font-size: 0.85rem; padding: 8px 16px; margin: 0;">Role Management</a>
    </div>

    <form action="{{ route('settings.update-theme') }}" method="POST">
        @csrf
        <div class="notes-grid" style="grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));">
            
            {{-- Primary Color --}}
            <div class="note-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="note-card-title">Warna Utama (Primary)</h3>
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $colors['theme_primary'] }}; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 800; color: {{ \App\Helpers\ThemeHelper::getContrastColor($colors['theme_primary']) }};">ABC</div>
                </div>
                <p style="font-size: 0.8rem; color: var(--color-text-muted); margin: 8px 0 15px;">Digunakan untuk Sidebar, Heading Navbar, dan Text judul utama.</p>
                <input type="color" name="theme_primary" value="{{ $colors['theme_primary'] }}" style="width: 100%; height: 45px; border-radius: 8px; border: 1.5px solid var(--color-border); cursor: pointer; background: white; padding: 4px;">
            </div>

            {{-- Secondary Color --}}
            <div class="note-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="note-card-title">Warna Sekunder (Buttons)</h3>
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $colors['theme_secondary'] }}; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 800; color: {{ \App\Helpers\ThemeHelper::getContrastColor($colors['theme_secondary']) }};">ABC</div>
                </div>
                <p style="font-size: 0.8rem; color: var(--color-text-muted); margin: 8px 0 15px;">Digunakan untuk Tombol utama, Link aktif, dan Aksi penting.</p>
                <input type="color" name="theme_secondary" value="{{ $colors['theme_secondary'] }}" style="width: 100%; height: 45px; border-radius: 8px; border: 1.5px solid var(--color-border); cursor: pointer; background: white; padding: 4px;">
            </div>

            {{-- Accent Color --}}
            <div class="note-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="note-card-title">Warna Aksen (Hover)</h3>
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $colors['theme_accent'] }}; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 800; color: {{ \App\Helpers\ThemeHelper::getContrastColor($colors['theme_accent']) }};">ABC</div>
                </div>
                <p style="font-size: 0.8rem; color: var(--color-text-muted); margin: 8px 0 15px;">Digunakan untuk efek Hover, Badge, dan Highlight elemen.</p>
                <input type="color" name="theme_accent" value="{{ $colors['theme_accent'] }}" style="width: 100%; height: 45px; border-radius: 8px; border: 1.5px solid var(--color-border); cursor: pointer; background: white; padding: 4px;">
            </div>

            {{-- Background Color --}}
            <div class="note-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="note-card-title">Warna Latar (Background)</h3>
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $colors['theme_bg'] }}; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 800; color: {{ \App\Helpers\ThemeHelper::getContrastColor($colors['theme_bg']) }};">ABC</div>
                </div>
                <p style="font-size: 0.8rem; color: var(--color-text-muted); margin: 8px 0 15px;">Warna dasar latar belakang seluruh aplikasi.</p>
                <input type="color" name="theme_bg" value="{{ $colors['theme_bg'] }}" style="width: 100%; height: 45px; border-radius: 8px; border: 1.5px solid var(--color-border); cursor: pointer; background: white; padding: 4px;">
            </div>

            {{-- Navbar Color --}}
            <div class="note-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="note-card-title">Warna Top Bar (Navbar)</h3>
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ substr($colors['theme_navbar'], 0, 7) }}; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 800; color: {{ \App\Helpers\ThemeHelper::getContrastColor(substr($colors['theme_navbar'], 0, 7)) }};">ABC</div>
                </div>
                <p style="font-size: 0.8rem; color: var(--color-text-muted); margin: 8px 0 15px;">Warna bilah atas (Navbar) yang berisi judul halaman.</p>
                {{-- Kita ambil 7 karakter pertama saja (#HEX) untuk input type color --}}
                <input type="color" name="theme_navbar" value="{{ substr($colors['theme_navbar'], 0, 7) }}" style="width: 100%; height: 45px; border-radius: 8px; border: 1.5px solid var(--color-border); cursor: pointer; background: white; padding: 4px;">
            </div>

        </div>

        <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
            <a href="{{ route('notes.index') }}" class="btn-back" style="margin: 0; padding: 12px 24px;">Batal</a>
            <button type="submit" class="btn-primary" style="padding: 12px 30px; font-weight: 600;">Simpan Perubahan Tema</button>
        </div>
    </form>
</div>
@endsection
