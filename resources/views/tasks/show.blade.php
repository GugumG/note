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

        @php
            $isOwner = $task->user_id === auth()->id();
            $isAssigned = $task->assigned_user_id === auth()->id();
            $needsACC = $task->status === 'complete' && !$task->is_approved;
        @endphp

        <div class="note-card" style="padding: 32px; border-left: 6px solid {{ $task->color ?? 'var(--color-secondary)' }};">
            <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 24px; flex-wrap: wrap;">
                @if($task->category)
                    <span style="font-size: 0.8rem; font-weight: 700; color: {{ \App\Helpers\ThemeHelper::getContrastColor($task->color ?? '#547792') }}; background: {{ $task->color ?? 'var(--color-secondary)' }}; padding: 4px 14px; border-radius: 14px; text-transform: uppercase;">
                        {{ $task->category }}
                    </span>
                @endif
                <span class="status-badge status-{{ str_replace(' ', '-', $task->status) }}" style="font-size: 0.9rem; padding: 4px 12px;">
                    {{ $task->status }}
                </span>

                @if($needsACC)
                    <span style="font-size: 0.85rem; font-weight: 800; color: #d97706; background: #fffbeb; padding: 4px 14px; border-radius: 14px; border: 1px solid #fcd34d;">
                        ⏳ MENUNGGU ACC
                    </span>
                @elseif($task->status === 'complete' && $task->is_approved)
                    <span style="font-size: 0.85rem; font-weight: 800; color: #059669; background: #ecfdf5; padding: 4px 14px; border-radius: 14px; border: 1px solid #6ee7b7;">
                        ✅ TERVERIFIKASI
                    </span>
                @endif

                @if($task->deadline)
                    <span class="deadline-label" style="font-size: 0.9rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
                        </svg>
                        Deadline: {{ $task->deadline->locale('id')->isoFormat('D MMMM YYYY') }}
                    </span>
                @endif
            </div>

            <div style="margin-bottom: 24px; padding: 15px; background: rgba(148, 180, 193, 0.05); border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 8px;">
                <p style="font-size: 0.9rem; color: var(--color-text-muted); display: flex; align-items: center; gap: 10px;">
                    <span style="width: 80px; font-weight: 600;">Pembuat:</span>
                    <span style="color: var(--color-primary); font-weight: 700;">{{ $task->user->name }}</span>
                </p>
                <p style="font-size: 0.9rem; color: var(--color-text-muted); display: flex; align-items: center; gap: 10px;">
                    <span style="width: 80px; font-weight: 600;">Pelaksana:</span>
                    <span style="color: var(--color-secondary); font-weight: 700;">{{ $task->assignedUser ? $task->assignedUser->name : 'Diri Sendiri' }}</span>
                </p>
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

            <div style="margin-top: 32px; display: flex; gap: 12px; border-top: 1px solid var(--color-border); padding-top: 24px; flex-wrap: wrap;">
                @if($isOwner)
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn-primary" style="background: var(--color-secondary); text-decoration: none; padding: 10px 20px; border-radius: 8px;">
                        Edit Task
                    </a>
                @endif
                
                @if($task->status != 'complete' && ($isOwner || $isAssigned))
                    <a href="{{ route('tasks.complete-form', $task->id) }}" class="btn-primary" style="background: var(--color-success); text-decoration: none; padding: 10px 20px; border-radius: 8px;">
                        Selesaikan Task
                    </a>
                @endif

                @if($needsACC && $isOwner)
                    <form action="{{ route('tasks.approve', $task->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary" style="background: #059669; height: 100%;">
                            ACC Selesai
                        </button>
                    </form>
                @endif

                @if($isOwner)
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus task ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-back" style="color: var(--color-error); border-color: var(--color-error); height: 100%;">
                            Hapus Task
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
