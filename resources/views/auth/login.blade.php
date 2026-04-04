@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h2 class="auth-title">Selamat Datang Kembali</h2>
    <p class="auth-subtitle">Kelola catatan dan tugas kantor Anda dengan aman.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="nama@perusahaan.com">
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <input type="password" id="password" name="password" class="form-input" required placeholder="••••••••">
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
            <input type="checkbox" id="remember" name="remember" style="width: 16px; height: 16px; cursor: pointer;">
            <label for="remember" style="font-size: 0.85rem; color: #7a96a8; cursor: pointer;">Ingat Saya</label>
        </div>

        <button type="submit" class="btn-auth">Masuk ke Akun</button>
    </form>

    <div class="auth-footer">
        Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar Sekarang</a>
    </div>
@endsection
