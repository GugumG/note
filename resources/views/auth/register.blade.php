@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
    <h2 class="auth-title">Buat Akun Baru</h2>
    <p class="auth-subtitle">Bergabunglah dan mulai kelola catatan Anda sekarang.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required autofocus placeholder="John Doe">
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="nama@perusahaan.com">
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <input type="password" id="password" name="password" class="form-input" required placeholder="Minimal 8 karakter">
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required placeholder="Ulangi kata sandi">
        </div>

        <button type="submit" class="btn-auth">Daftar Akun Baru</button>
    </form>

    <div class="auth-footer">
        Sudah memiliki akun? <a href="{{ route('login') }}" class="auth-link">Login Sekarang</a>
    </div>
@endsection
