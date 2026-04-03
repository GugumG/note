@extends('layouts.app')

@section('title', 'Detail Task')
@section('page-title', 'Detail Task')
@section('page-breadcrumb', 'NoteApp / Tasks / Detail')

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
                <h2 class="form-title">{{ $task->title }}</h2>
                <p class="form-subtitle">Detail jadwal kegiatan</p>
            </div>
        </div>

        <div class="note-card" style="padding: 32px; border-left: 5px solid var(--color-secondary);">
            <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 24px;">
                <span class="status-badge status-{{ str_replace(' ', '-', $task->status) }}" style="font-size: 0.9rem; padding: 4px 12px;">
                    {{ $task->status }}
                </span>
                @if($task->deadline)
                    <span class="deadline-label" style="font-size: 0.9rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
                        </svg>
                        Deadline: {{ $task->deadline->locale('id')->isoFormat('D MMMM YYYY') }}
                    </span>
                @endif
            </div>

            <div style="font-size: 1.1rem; line-height: 1.8; color: var(--color-text-primary); white-space: pre-wrap; background: #fcfaf7; padding: 24px; border-radius: 12px; border: 1px solid var(--color-border);">
                {{ $task->content ?: 'Tidak ada deskripsi tambahan.' }}
            </div>

            @if($task->status == 'complete' && $task->completion_data)
                <div style="margin-top: 24px; padding: 20px; background: #eef2ff; border-radius: 12px; border: 1px dashed #6366f1;">
                    <h4 style="color: #4338ca; font-size: 0.9rem; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Bukti Penyelesaian:</h4>
                    <div style="font-size: 1rem; color: #1e1b4b;">
                        @if(filter_var($task->completion_data, FILTER_VALIDATE_URL))
                            <a href="{{ $task->completion_data }}" target="_blank" style="color: #4338ca; text-decoration: underline; word-break: break-all;">{{ $task->completion_data }}</a>
                        @else
                            {{ $task->completion_data }}
                        @endif
                    </div>
                </div>
            @endif

            <div style="margin-top: 32px; display: flex; gap: 12px; border-top: 1px solid var(--color-border); padding-top: 24px;">
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn-primary" style="background: var(--color-secondary);">
                    Edit Task
                </a>
                @if($task->status != 'complete')
                    <a href="{{ route('tasks.complete-form', $task->id) }}" class="btn-primary" style="background: var(--color-success);">
                        Selesaikan Task
                    </a>
                @endif
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus task ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-back" style="color: var(--color-error); border-color: var(--color-error);">
                        Hapus Task
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
