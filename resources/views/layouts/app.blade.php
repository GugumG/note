<!DOCTYPE html>
<html lang="id">

{{--
    ============================================================
    FILE: resources/views/layouts/app.blade.php
    STEP [8] — Layout utama (Master Template) yang digunakan oleh
               semua halaman note (STEP [5],[6],[7]).
               Layout ini berisi: Navbar, Sidebar, dan Content Area.
               CSS & JS di-load melalui Vite (STEP [8a]).
    ============================================================
--}}

<head>
    {{-- [8a] Meta dasar HTML --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- [8b] CSRF Token untuk keamanan form POST/PUT/DELETE --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NoteApp') — Dashboard</title>

    {{-- [8c] Google Fonts: Inter untuk tipografi modern --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- [8d] Vite: load app.css (Tailwind) dan app.js dari resources/ --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- [New] Dynamic Theme Injection --}}
    @php
        $primary   = \App\Models\Setting::get('theme_primary', '#213448');
        $secondary = \App\Models\Setting::get('theme_secondary', '#547792');
        $accent    = \App\Models\Setting::get('theme_accent', '#94B4C1');
        $bg        = \App\Models\Setting::get('theme_bg', '#F3F4F4');
        $navbar    = \App\Models\Setting::get('theme_navbar', '#EAE0CFec');

        $onPrimary   = \App\Helpers\ThemeHelper::getContrastColor($primary);
        $onSecondary = \App\Helpers\ThemeHelper::getContrastColor($secondary);
        $onAccent    = \App\Helpers\ThemeHelper::getContrastColor($accent);
        $onBg        = \App\Helpers\ThemeHelper::getContrastColor($bg);
        $onNavbar    = \App\Helpers\ThemeHelper::getContrastColor($navbar);
    @endphp
    <style>
        :root {
            --color-primary: {{ $primary }};
            --color-secondary: {{ $secondary }};
            --color-accent: {{ $accent }};
            --color-bg: {{ $bg }};
            --color-navbar: {{ $navbar }};
            
            /* Dynamic Text Colors Based on Contrast */
            --color-text-on-primary: {{ $onPrimary }};
            --color-text-on-secondary: {{ $onSecondary }};
            --color-text-on-accent: {{ $onAccent }};
            --color-text-on-bg: {{ $onBg }};
            --color-text-on-navbar: {{ $onNavbar }};

            /* Also update the primary text to use the onBg color if needed */
            --color-text-primary: {{ $onBg }};
            
            /* Dynamic dark tones based on the primary */
            --color-primary-dark: {{ $primary }}dd; 
        }
    </style>
</head>

<body class="app-body">

    {{-- ====================================================
         [8e] SIDEBAR — Panel navigasi di sisi kiri.
              Link sidebar mengarah ke routes (STEP [4]).
    ==================================================== --}}
    <aside class="sidebar" id="sidebar">

        {{-- [8f] Logo / Brand aplikasi --}}
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
                    <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-7 3a1 1 0 110 2 1 1 0 010-2zm3 10H9a1 1 0 010-2h6a1 1 0 010 2zm0-4H9a1 1 0 010-2h6a1 1 0 010 2z"/>
                </svg>
            </div>
            <span class="sidebar-brand-text">NoteApp</span>
        </div>

        {{-- [8g] Menu navigasi sidebar --}}
        <nav class="sidebar-nav">
            <p class="sidebar-section-label">Menu</p>

            {{-- [8h] Link ke halaman Daftar Notes (STEP [5]) --}}
            <a href="{{ route('notes.index') }}"
               class="sidebar-link {{ request()->routeIs('notes.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
                </svg>
                <span>Semua Catatan</span>
            </a>

            {{-- [8i] Link ke halaman Buat Note Baru (STEP [6]) --}}
            <a href="{{ route('notes.create') }}"
               class="sidebar-link {{ request()->routeIs('notes.create') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
                <span>Buat Catatan</span>
            </a>

            {{-- [New] Link ke halaman Semua Task --}}
            <a href="{{ route('tasks.index') }}"
               class="sidebar-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2zm4 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/>
                </svg>
                <span>Semua Task</span>
            </a>

            {{-- [New] Link ke halaman Pengaturan (Theme/User/Role) --}}
            <a href="{{ route('settings.theme') }}"
               class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
               style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M19.14 12.94c.04-.3.06-.61.06-.94s-.02-.64-.06-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32c-.12-.22-.39-.31-.61-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 00-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.09-.49 0-.61.22l-1.92 3.32a.49.49 0 00.12.61l2.03 1.58c-.04.3-.06.61-.06.94s.02.64.06.94l-2.03 1.58a.49.49 0 00-.12.61l1.92 3.32c.12.22.39.31.61.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.57 1.62-.94l2.39.96c.22.09.49 0 .61-.22l1.92-3.32a.49.49 0 00-.12-.61l-2.03-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                </svg>
                <span>Pengaturan</span>
            </a>
        </nav>

        {{-- [8j] Footer sidebar --}}
        <div class="sidebar-footer">
            <div class="sidebar-footer-info">
                <div class="sidebar-footer-avatar">NA</div>
                <div>
                    <p class="sidebar-footer-name">Note App</p>
                    <p class="sidebar-footer-sub">v1.0.0</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ====================================================
         [8k] MAIN WRAPPER — Area konten utama (kanan sidebar)
    ==================================================== --}}
    <div class="main-wrapper">

        {{-- [8l] NAVBAR — Header bar di atas konten utama --}}
        <header class="navbar">
            {{-- [8m] Tombol toggle sidebar (mobile) --}}
            <button class="navbar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                </svg>
            </button>

            {{-- [8n] Judul halaman dinamis (diisi dari tiap view menggunakan @yield) --}}
            <div class="navbar-title">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <p class="navbar-breadcrumb">@yield('page-breadcrumb', 'NoteApp')</p>
            </div>

            {{-- [8o] Info tanggal di navbar --}}
            <div class="navbar-right">
                <div class="navbar-date">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                    <span>{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                </div>
            </div>
        </header>

        {{-- [8p] CONTENT AREA — Konten utama dari masing-masing halaman (STEP [5],[6],[7]) --}}
        <main class="content-area">

            {{-- [8q] Flash message sukses dari Controller (STEP [3m],[3s],[3v]) --}}
            @if(session('success'))
            <div class="alert-success" id="alertSuccess">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('alertSuccess').remove()" class="alert-close">×</button>
            </div>
            @endif

            {{-- [8r] Slot konten: masing-masing view mengisi bagian ini --}}
            @yield('content')
        </main>
    </div>

    {{-- [8s] Overlay untuk mobile sidebar --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- [8t] Script toggle sidebar untuk mobile --}}
    <script>
        // [8u] Toggle sidebar saat tombol hamburger ditekan (navbar toggle STEP [8m])
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    </script>

    {{-- [8v] Slot script tambahan dari child view (misal Quill JS di STEP [6],[7]) --}}
    @stack('scripts')

</body>
</html>
