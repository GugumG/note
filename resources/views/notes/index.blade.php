{{--
    ============================================================
    FILE: resources/views/notes/index.blade.php
    STEP [5] — Halaman LIST VIEW (Daftar Catatan).
               Halaman pertama yang ditampilkan.
               Data $notes dikirim dari NoteController@index (STEP [3b]).
               Menggunakan layout app.blade.php (STEP [8]).
    ============================================================
--}}

@extends('layouts.app') {{-- [5a] Extend layout utama (STEP [8]) --}}

{{-- [5b] Set judul halaman untuk Navbar (lihat STEP [8n]) --}}
@section('title', 'Daftar Catatan')
@section('page-title', 'Daftar Catatan')
@section('page-breadcrumb', 'NoteApp / Notes')
@section('content')
    <div class="dashboard-container">
        {{-- [Section 70%] Daftar Catatan --}}
        <div class="notes-column">
            <div class="page-header">
                <div class="page-header-left">
                    <h2 class="page-heading">Catatan Saya</h2>
                    <p class="page-subheading">{{ $notes->count() }} catatan tersimpan</p>
                </div>
                <a href="{{ route('notes.create') }}" class="btn-primary" id="btn-add-note">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                    <span>Tambah Catatan</span>
                </a>
            </div>

            {{-- [New] Search/Filter Hashtag --}}
            <form action="{{ route('notes.index') }}" method="GET" class="search-filter-wrapper">
                <div class="search-input-group">
                    <div class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                            <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           name="hashtag" 
                           class="search-input" 
                           placeholder="Cari tema atau hashtag..." 
                           value="{{ request('hashtag') }}">
                    <div class="search-actions">
                        @if(request('hashtag'))
                            <a href="{{ route('notes.index') }}" class="search-clear-btn" title="Hapus Pencarian">×</a>
                        @endif
                        <button type="submit" class="search-submit-btn">Cari</button>
                    </div>
                </div>
            </form>

            @if($notes->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
                        </svg>
                    </div>
                    <h3>Belum ada catatan</h3>
                    <p>Mulai buat catatan pertamamu sekarang!</p>
                </div>
            @else
                <div class="notes-grid">
                    @foreach($notes as $note)
                    <div class="note-card {{ $note->is_pinned ? 'is-pinned' : '' }}" id="note-card-{{ $note->id }}">
                        <div class="note-card-date">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="12" height="12">
                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
                            </svg>
                            {{ $note->note_date->locale('id')->isoFormat('D MMM YYYY') }}
                        </div>
                        <h3 class="note-card-title">{{ $note->title }}</h3>
                        <div class="note-card-excerpt">
                            {{ Str::limit(strip_tags($note->content), 120, '...') }}
                        </div>

                        {{-- [New] Display Hashtags --}}
                        @if($note->hashtags)
                            <div class="note-card-hashtags">
                                @foreach(explode(' ', $note->hashtags) as $tag)
                                    @if(trim($tag) != '')
                                        <span class="hashtag-badge">{{ $tag }}</span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <div class="note-card-actions">
                            <a href="{{ route('notes.show', $note->id) }}" class="action-btn action-btn-view" title="Lihat">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </a>
                            <form action="{{ route('notes.toggle-pin', $note->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="action-btn" title="{{ $note->is_pinned ? 'Lepas Pin' : 'Pin Catatan' }}" style="color: {{ $note->is_pinned ? 'var(--color-primary)' : 'var(--color-text-muted)' }};">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                        <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h7v6h2v-6h7v-2l-2-2z"/>
                                    </svg>
                                </button>
                            </form>
                            <a href="{{ route('notes.edit', $note->id) }}" class="action-btn action-btn-edit" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 000-1.41l-2.34-2.34a1 1 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                </svg>
                            </a>
                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- [Section 30%] Task Scheduler Sidebar --}}
        <div class="tasks-column">
            <div class="page-header" style="margin-bottom: 24px;">
                <div class="page-header-left">
                    <h2 class="page-heading">TASK</h2>
                    <p class="page-subheading">{{ $tasks->count() }} task aktif</p>
                </div>
                <a href="{{ route('tasks.create') }}" class="btn-primary" style="padding: 8px 12px; font-size: 0.8rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                    <span>Add</span>
                </a>
            </div>

            <div class="tasks-vertical-list">
                @forelse($tasks as $task)
                    <div class="note-card task-sidebar-item {{ $task->is_pinned ? 'is-pinned' : '' }}" style="border-left: 5px solid {{ $task->color ?? 'var(--color-secondary)' }}; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="note-card-date">
                                <div style="display: flex; gap: 4px; align-items: center;">
                                    @if($task->urgency_label)
                                        <span class="status-badge status-{{ strtolower($task->urgency_label) }}" style="font-size: 0.62rem; padding: 1px 5px;">
                                            {{ $task->urgency_label }}
                                        </span>
                                    @endif
                                    <span class="status-badge status-{{ str_replace(' ', '-', $task->status) }}" style="font-size: 0.62rem; padding: 1px 5px;">
                                        {{ $task->status }}
                                    </span>
                                </div>
                                @if($task->deadline)
                                    <span style="margin-left: 8px; opacity: 0.7; font-size: 0.75rem;">
                                        {{ $task->deadline->isoFormat('D MMM') }}
                                    </span>
                                @endif
                            </div>
                            @if($task->category)
                                <span style="font-size: 0.65rem; font-weight: 700; color: white; background: {{ $task->color ?? 'var(--color-secondary)' }}; padding: 1px 8px; border-radius: 10px; text-transform: uppercase;">
                                    {{ $task->category }}
                                </span>
                            @endif
                        </div>
                        <h3 class="note-card-title" style="font-size: 0.95rem; margin: 8px 0;">{{ $task->title }}</h3>
                        <div class="note-card-excerpt" style="font-size: 0.8rem; -webkit-line-clamp: 2; line-clamp: 2;">
                            {{ Str::limit($task->content, 100) }}
                        </div>
                        <div class="note-card-actions" style="margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                            <a href="{{ route('tasks.show', $task->id) }}" class="action-btn action-btn-view" title="Lihat">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </a>
                            <form action="{{ route('tasks.toggle-pin', $task->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="action-btn" title="{{ $task->is_pinned ? 'Lepas Pin' : 'Pin Task' }}" style="color: {{ $task->is_pinned ? 'var(--color-secondary)' : 'var(--color-text-muted)' }};">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14">
                                        <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h7v6h2v-6h7v-2l-2-2z"/>
                                    </svg>
                                </button>
                            </form>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="action-btn action-btn-edit" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 000-1.41l-2.34-2.34a1 1 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                </svg>
                            </a>
                            @if($task->status != 'complete')
                                <a href="{{ route('tasks.complete-form', $task->id) }}" class="action-btn" title="Complete" style="background: var(--color-success); color: white; margin-left: 5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px 20px; color: var(--color-text-muted); font-size: 0.9rem; background: white; border-radius: 12px; border: 1px dashed var(--color-border);">
                        Belum ada task.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@push('scripts')
{{-- Modal JS removed --}}
@endpush

