@extends('layouts.app')

@section('title', 'Semua Task')
@section('page-title', 'Daftar Semua Task')
@section('page-breadcrumb', 'NoteApp / Tasks')

@section('content')
    <div class="page-header">
        <div class="page-header-left">
            <h2 class="page-heading">Manajemen Task</h2>
            <p class="page-subheading">{{ $tasks->count() }} task tercatat</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
            <span>Tambah Task</span>
        </a>
    </div>

    @if($tasks->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2zm4 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/>
                </svg>
            </div>
            <h3>Belum ada task</h3>
            <p>Mulai jadwalkan kegiatanmu agar lebih produktif!</p>
            <a href="{{ route('tasks.create') }}" class="btn-primary">
                Buat Task Pertama
            </a>
        </div>
    @else
        <div class="notes-grid"> {{-- Reuse grid style --}}
            @foreach($tasks as $task)
            <div class="note-card task-full-card {{ $task->is_pinned ? 'is-pinned' : '' }}" style="border-left: 5px solid {{ $task->status == 'complete' ? '#1e40af' : '#547792' }}">
                <div class="note-card-date">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="12" height="12">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
                    </svg>
                    Deadline: {{ $task->deadline ? $task->deadline->locale('id')->isoFormat('D MMM YYYY') : 'N/A' }}
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <h3 class="note-card-title">{{ $task->title }}</h3>
                    <span class="status-badge status-{{ str_replace(' ', '-', $task->status) }}">
                        {{ $task->status }}
                    </span>
                </div>

                <div class="note-card-excerpt">
                    {{ Str::limit($task->content, 150) }}
                </div>

                @if($task->status == 'complete' && $task->completed_at)
                    <div class="completion-details" style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                            <span class="note-card-date" style="background: #e0f2fe; color: #0369a1;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="12" height="12">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                </svg>
                                Selesai: {{ $task->completed_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}
                            </span>
                            
                            @if($task->days_diff !== null)
                                <span class="completion-badge {{ $task->days_diff >= 0 ? 'completion-early' : 'completion-late' }}">
                                    @if($task->days_diff > 0)
                                        Selesai {{ $task->days_diff }} hari lebih awal
                                    @elseif($task->days_diff < 0)
                                        Terlambat {{ abs($task->days_diff) }} hari
                                    @else
                                        Selesai tepat waktu
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                @if($task->status == 'complete' && $task->completion_data)
                    <div style="margin-top: 10px; padding: 10px; background: #f0f7ff; border-radius: 8px; font-size: 0.85rem; border: 1px dashed #abc; ">
                        <strong>Penyelesaian:</strong><br>
                        @if(filter_var($task->completion_data, FILTER_VALIDATE_URL))
                            <a href="{{ $task->completion_data }}" target="_blank" style="color: var(--color-secondary); word-break: break-all;">{{ $task->completion_data }}</a>
                        @else
                            {{ $task->completion_data }}
                        @endif
                    </div>
                @endif

                <div class="note-card-actions">
                    <a href="{{ route('tasks.show', $task->id) }}" class="action-btn action-btn-view" title="Lihat">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </a>
                    <form action="{{ route('tasks.toggle-pin', $task->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="action-btn" title="{{ $task->is_pinned ? 'Lepas Pin' : 'Pin Task' }}" style="color: {{ $task->is_pinned ? 'var(--color-primary)' : 'var(--color-text-muted)' }};">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h7v6h2v-6h7v-2l-2-2z"/>
                            </svg>
                        </button>
                    </form>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="action-btn action-btn-edit" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 000-1.41l-2.34-2.34a1 1 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                    </a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn action-btn-delete" title="Hapus" onclick="return confirm('Hapus task ini?')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                            </svg>
                        </button>
                    </form>
                    @if($task->status != 'complete')
                        <a href="{{ route('tasks.complete-form', $task->id) }}" class="btn-task-action btn-task-complete" style="margin-left: 8px; text-decoration: none; display: flex; align-items: center; justify-content: center;">Complete</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif

@endsection

@push('scripts')
{{-- Modal JS removed --}}
@endpush

