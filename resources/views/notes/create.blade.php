{{--
    ============================================================
    FILE: resources/views/notes/create.blade.php
    STEP [6] — Halaman FORM BUAT CATATAN BARU.
               Form ini disubmit ke NoteController@store (STEP [3g])
               via route notes.store (STEP [4c]).
               Menggunakan layout app.blade.php (STEP [8]).
               Quill RTE di-initialize di bagian @push('scripts') (STEP [6j]).
    ============================================================
--}}

@extends('layouts.app') {{-- [6a] Extend layout utama (STEP [8]) --}}

{{-- [6b] Set judul halaman untuk Navbar (lihat STEP [8n]) --}}
@section('title', 'Buat Catatan')
@section('page-title', 'Buat Catatan Baru')
@section('page-breadcrumb', 'NoteApp / Notes / Buat')

@section('content')

    {{-- [6c] Wrapper form container --}}
    <div class="form-container">

        {{-- [6d] Header form --}}
        <div class="form-header">
            {{-- [6e] Tombol kembali ke list view (STEP [5]) --}}
            <a href="{{ route('notes.index') }}" class="btn-back" id="btn-back-create">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Kembali
            </a>
            <div class="form-header-info">
                <h2 class="form-title">Catatan Baru</h2>
                <p class="form-subtitle">Tulis ide dan catatanmu di sini</p>
            </div>
        </div>

        {{-- [6f] Form utama: POST ke route notes.store (STEP [4c]) → NoteController@store (STEP [3g]) --}}
        <form action="{{ route('notes.store') }}"
              method="POST"
              class="note-form"
              id="create-note-form">
            @csrf {{-- [6g] Token CSRF untuk keamanan (STEP [8b]) --}}

            {{-- [6h] FIELD JUDUL --}}
            <div class="form-group">
                <label for="title" class="form-label">
                    <span class="label-icon">📝</span>
                    Judul Catatan <span class="required">*</span>
                </label>
                <input type="text"
                       id="title"
                       name="title"
                       class="form-input {{ $errors->has('title') ? 'is-error' : '' }}"
                       placeholder="Masukkan judul catatan..."
                       value="{{ old('title') }}"
                       required>
                {{-- [6h1] Tampilkan error validasi dari Controller (STEP [3h]) --}}
                @error('title')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- [6i] FIELD TANGGAL (otomatis terisi tanggal hari ini dari Controller STEP [3f]) --}}
            <div class="form-group">
                <label for="note_date" class="form-label">
                    <span class="label-icon">📅</span>
                    Tanggal <span class="required">*</span>
                </label>
                <input type="date"
                       id="note_date"
                       name="note_date"
                       class="form-input {{ $errors->has('note_date') ? 'is-error' : '' }}"
                       value="{{ old('note_date', $today) }}"
                       required>
                @error('note_date')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- [New] FIELD HASHTAG --}}
            <div class="form-group">
                <label for="hashtags" class="form-label">
                    <span class="label-icon">#️⃣</span>
                    Hashtag (Tema)
                </label>
                <input type="text"
                       id="hashtags"
                       name="hashtags"
                       class="form-input {{ $errors->has('hashtags') ? 'is-error' : '' }}"
                       placeholder="Contoh: #fashion #belajar #ide"
                       value="{{ old('hashtags') }}">
                <p class="form-subtitle" style="font-size: 0.75rem; margin-top: 4px;">Pisahkan dengan spasi untuk beberapa hashtag</p>
                @error('hashtags')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- [6j] FIELD ISI CATATAN — Quill Rich Text Editor --}}
            <div class="form-group">
                <label class="form-label">
                    <span class="label-icon">✍️</span>
                    Isi Catatan <span class="required">*</span>
                </label>

                {{-- [6j1] Container Quill Editor (div ini yang di-render oleh Quill.js) --}}
                <div id="quill-editor" class="quill-editor-container">
                    {{-- [6j2] Isi awal editor dari old('content') jika ada validasi error --}}
                    {!! old('content') !!}
                </div>

                {{-- [6j3] Hidden input: menampung HTML output dari Quill.
                           Disubmit bersama form ke NoteController@store (STEP [3g]).
                           Nilai diisi oleh JavaScript (lihat @push('scripts') di bawah) --}}
                <input type="hidden" name="content" id="content-input">

                @error('content')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- [6k] Tombol aksi form --}}
            <div class="form-actions">
                <a href="{{ route('notes.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-primary" id="btn-submit-create">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                        <path d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                    </svg>
                    Simpan Catatan
                </button>
            </div>
        </form>
    </div>

@endsection

{{-- ============================================================
     STEP [6L] — JavaScript section untuk Quill RTE
     Script ini di-push ke @stack('scripts') di layout (STEP [8v])
     sehingga dieksekusi setelah body selesai dimuat.
============================================================ --}}
@push('scripts')

{{-- [6m] Load Quill.js dari CDN -- --}}
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    // ============================================================
    // [6n] Inisialisasi Quill Editor
    //      Editor ini mengisi #quill-editor (STEP [6j1])
    //      dan hasilnya dikirim ke #content-input (STEP [6j3])
    // ============================================================
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Tulis isi catatanmu di sini...',
        modules: {
            // [6o] Toolbar Quill: tombol-tombol formatting yang tersedia
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, false] }],      // Heading
                    ['bold', 'italic', 'underline', 'strike'], // Format teks
                    [{ 'color': [] }, { 'background': [] }],  // Warna font & background
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }], // List
                    [{ 'indent': '-1' }, { 'indent': '+1' }],  // Indent
                    ['blockquote', 'code-block'],              // Blockquote / Code
                    ['link', 'image'],                         // [6p] Tombol insert link & gambar
                    ['clean']                                   // Hapus formatting
                ],
                // [6q] Handler untuk tombol "image": upload ke server (STEP [3w])
                handlers: {
                    image: imageUploadHandler
                }
            }
        }
    });

    // ============================================================
    // [6r] Fungsi upload gambar ke server saat tombol image di Quill diklik.
    //      Gambar diupload ke route notes.upload-image (STEP [4e])
    //      yang ditangani NoteController@uploadImage (STEP [3w]).
    // ============================================================
    function imageUploadHandler() {
        // [6s] Buat element input file sementara
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        // [6t] Saat file dipilih, upload ke server via fetch
        input.onchange = async () => {
            const file = input.files[0];
            if (!file) return;

            // [6u] Siapkan FormData dengan file gambar
            const formData = new FormData();
            formData.append('image', file);

            // [6v] Tambahkan CSRF token (dari meta tag STEP [8b]) untuk keamanan
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            try {
                // [6w] Kirim request POST ke route upload-image (STEP [4e])
                const response = await fetch('{{ route('notes.upload-image') }}', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                // [6x] Insert URL gambar ke editor Quill di posisi kursor
                const range = quill.getSelection();
                quill.insertEmbed(range ? range.index : 0, 'image', data.url);

            } catch (error) {
                alert('Gagal mengupload gambar. Coba lagi.');
            }
        };
    }

    // ============================================================
    // [6y] Sinkronisasi konten Quill ke hidden input (#content-input)
    //      sebelum form disubmit (STEP [6k]).
    //      Tanpa ini, content tidak akan terkirim ke Controller (STEP [3g]).
    // ============================================================
    const form = document.getElementById('create-note-form');
    const contentInput = document.getElementById('content-input');

    form.addEventListener('submit', function() {
        // [6z] Ambil HTML dari Quill dan masukkan ke hidden input
        contentInput.value = quill.root.innerHTML;
    });

    // [6z1] Jika ada old value (gagal validasi), restore ke editor
    const oldContent = contentInput.value;
    if (oldContent && oldContent.trim() !== '') {
        quill.root.innerHTML = oldContent;
    }
</script>
@endpush
