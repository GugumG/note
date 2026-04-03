{{--
    ============================================================
    FILE: resources/views/notes/show.blade.php
    STEP [10] — Halaman DETAIL / VIEW CATATAN.
                Data $note dikirim dari NoteController@show (STEP [3bb]).
                Route: GET /notes/{note} → notes.show (STEP [4c]).
                Menggunakan layout app.blade.php (STEP [8]).
    ============================================================
--}}

@extends('layouts.app') {{-- [10a] Extend layout utama (STEP [8]) --}}

{{-- [10b] Set judul halaman untuk Navbar (lihat STEP [8n]) --}}
@section('title', $note->title)
@section('page-title', 'Detail Catatan')
@section('page-breadcrumb', 'NoteApp / Notes / Detail')

@section('content')

    {{-- [10c] Wrapper konten detail --}}
    <div class="form-container">

        {{-- [10d] Header: tombol kembali + info judul --}}
        <div class="form-header">
            {{-- [10e] Kembali ke list view (STEP [5]) --}}
            <a href="{{ route('notes.index') }}" class="btn-back" id="btn-back-show">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Kembali
            </a>
            <div class="form-header-info">
                <h2 class="form-title">{{ $note->title }}</h2>
                {{-- [10f] Tampilkan tanggal catatan --}}
                <p class="form-subtitle">
                    <span class="show-date-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="13" height="13">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
                        </svg>
                        {{ $note->note_date->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </span>
                </p>
            </div>

            {{-- [10g] Tombol aksi di header: Edit & Delete --}}
            <div class="show-header-actions">
                {{-- [10h] Tombol Edit → route notes.edit (STEP [4c]) → NoteController@edit (STEP [3n]) --}}
                <a href="{{ route('notes.edit', $note->id) }}"
                   class="btn-action-outline btn-action-edit"
                   id="btn-edit-show"
                   title="Edit Catatan">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 000-1.41l-2.34-2.34a1 1 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                    </svg>
                    Edit
                </a>

                {{-- [10i] Tombol Delete → form DELETE (STEP [4c]) → NoteController@destroy (STEP [3t]) --}}
                <form action="{{ route('notes.destroy', $note->id) }}"
                      method="POST"
                      id="delete-form-show"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn-action-outline btn-action-delete"
                            title="Hapus Catatan"
                            onclick="return confirm('Yakin ingin menghapus catatan ini?')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- [10j] Kartu konten utama catatan --}}
        <div class="show-card">

            {{-- [10k] Informasi meta catatan --}}
            <div class="show-meta">
                <div class="show-meta-item">
                    <span class="show-meta-label">Dibuat</span>
                    <span class="show-meta-value">{{ $note->created_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</span>
                </div>
                @if($note->updated_at->ne($note->created_at))
                <div class="show-meta-divider"></div>
                <div class="show-meta-item">
                    <span class="show-meta-label">Diperbarui</span>
                    <span class="show-meta-value">{{ $note->updated_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</span>
                </div>
                @endif
            </div>

            {{-- [10l] Garis pemisah --}}
            <div class="show-divider"></div>

            {{-- [10m] Konten catatan (HTML dari Quill editor — STEP [6],[7]).
                       Ditampilkan dengan {!! !!} karena berisi HTML tag (bold, image, dll).
                       Class "ql-editor" dipakai agar styling Quill tetap berlaku. --}}
            <div class="show-content ql-editor" id="note-content">
                {!! $note->content !!}
            </div>
        </div>

    </div>

@endsection

{{-- [10n] Load Quill CSS agar styling konten (bold, list, image, dll) tetap tampil dengan benar --}}
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    /* [10o] Override: hilangkan border Quill di halaman view-only */
    #note-content.ql-editor {
        padding: 0;
        border: none !important;
        box-shadow: none !important;
        min-height: unset;
        font-family: 'Inter', sans-serif;
        font-size: 0.97rem;
        line-height: 1.8;
        color: var(--color-text-primary);
    }

    #note-content.ql-editor img {
        max-width: 100%;
        border-radius: 10px;
        margin: 12px 0;
        box-shadow: 0 4px 14px rgba(33,52,72,0.12);
    }
</style>
@endpush
