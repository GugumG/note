@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('page-breadcrumb', 'Pengaturan / User')

@section('content')
<div class="dashboard-container" style="flex-direction: column; gap: 0;">
    <div class="page-header">
        <div class="page-header-left">
            <h2 class="page-heading">Daftar Akun Terdaftar</h2>
            <p class="page-subheading">{{ $users->count() }} akun dalam sistem</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <span>Tambah User Baru</span>
        </a>
    </div>

    <div class="note-card" style="padding: 0; overflow: hidden; border: 1px solid var(--color-border);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
                <thead style="background: rgba(148, 180, 193, 0.1); border-bottom: 2px solid var(--color-border);">
                    <tr>
                        <th style="padding: 15px 20px; font-weight: 700; color: var(--color-primary);">USER</th>
                        <th style="padding: 15px 20px; font-weight: 700; color: var(--color-primary);">EMAIL</th>
                        <th style="padding: 15px 20px; font-weight: 700; color: var(--color-primary);">ROLE</th>
                        <th style="padding: 15px 20px; font-weight: 700; color: var(--color-primary);">TEAM</th>
                        <th style="padding: 15px 20px; font-weight: 700; color: var(--color-primary);">BERGABUNG</th>
                        <th style="padding: 15px 20px; font-weight: 700; color: var(--color-primary); text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="border-bottom: 1px solid rgba(148, 180, 193, 0.15); transition: background 0.2s;" onmouseover="this.style.background='rgba(148, 180, 193, 0.05)'" onmouseout="this.style.background='white'">
                        <td style="padding: 15px 20px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-secondary); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <span style="font-weight: 600; color: var(--color-primary);">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td style="padding: 15px 20px; color: var(--color-text-muted);">{{ $user->email }}</td>
                        <td style="padding: 15px 20px;">
                            <span class="status-badge" style="background: {{ $user->role === 'administrator' ? '#ef4444' : ($user->role === 'leader' ? '#3b82f6' : '#64748b') }}; color: white; font-size: 0.7rem; padding: 2px 10px; border-radius: 12px; font-weight: 700; text-transform: uppercase;">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td style="padding: 15px 20px; color: var(--color-text-muted);">
                            {{ $user->team ?: '-' }}
                        </td>
                        <td style="padding: 15px 20px; color: var(--color-text-muted);">
                            {{ $user->created_at->locale('id')->isoFormat('D MMM YYYY') }}
                        </td>
                        <td style="padding: 15px 20px; text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                <a href="{{ route('users.edit', $user->id) }}" class="action-btn action-btn-edit" title="Edit User">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 000-1.41l-2.34-2.34a1 1 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                    </svg>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini? Semua data terkait mungkin akan hilang.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Hapus User">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
        <a href="{{ route('settings.theme') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            <span>Kembali ke Tema</span>
        </a>
    </div>
</div>
@endsection
