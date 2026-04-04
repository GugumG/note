@extends('layouts.app')

@section('title', $title)
@section('page-title', $title)
@section('page-breadcrumb', 'Pengaturan')

@section('content')
<div class="empty-state">
    <div class="empty-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="40" height="40">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
        </svg>
    </div>
    <h3>Segera Hadir</h3>
    <p>Fitur <strong>{{ $title }}</strong> sedang dalam tahap pengembangan untuk versi aplikasi kantor mendatang.</p>
    <a href="{{ route('settings.theme') }}" class="btn-primary" style="margin-top: 15px;">Kembali ke Tema</a>
</div>
@endsection
