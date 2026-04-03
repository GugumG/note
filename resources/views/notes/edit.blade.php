{{--
    ============================================================
    FILE: resources/views/notes/edit.blade.php
    STEP [7] — Halaman FORM EDIT CATATAN.
               Data $note dikirim dari NoteController@edit (STEP [3n]).
               Form disubmit ke NoteController@update (STEP [3p])
               via route notes.update (STEP [4c]).
               Menggunakan layout app.blade.php (STEP [8]).
               Quill RTE di-initialize di @push('scripts') (STEP [7j]).
    ============================================================
--}}

@extends('layouts.app') {{-- [7a] Extend layout utama (STEP [8]) --}}

{{-- [7b] Set judul halaman untuk Navbar (lihat STEP [8n]) --}}
@section('title', 'Edit Catatan')
@section('page-title', 'Edit Catatan')
@section('page-breadcrumb', 'NoteApp / Notes / Edit')

@section('content')

    {{-- [7c] Wrapper form container --}}
    <div class="form-container">

        {{-- [7d] Header form --}}
        <div class="form-header">
            {{-- [7e] Tombol kembali ke list view (STEP [5]) --}}
            <a href="{{ route('notes.index') }}" class="btn-back" id="btn-back-edit">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Kembali
            </a>
            <div class="form-header-info">
                <h2 class="form-title">Edit Catatan</h2>
                {{-- [7f] Tampilkan judul catatan yang sedang diedit --}}
                <p class="form-subtitle">Mengedit: <strong>{{ $note->title }}</strong></p>
            </div>
        </div>

        {{-- [7g] Form update: POST dengan method override PUT ke route notes.update (STEP [4c])
                  → NoteController@update (STEP [3p]) --}}
        <form action="{{ route('notes.update', $note->id) }}"
              method="POST"
              class="note-form"
              id="edit-note-form">
            @csrf           {{-- [7h] Token CSRF untuk keamanan (STEP [8b]) --}}
            @method('PUT')  {{-- [7i] Override method ke PUT karena HTML hanya support GET/POST --}}

            {{-- [7j] FIELD JUDUL — Pre-filled dengan nilai dari $note (STEP [3n]) --}}
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
                       value="{{ old('title', $note->title) }}"
                       required>
                @error('title')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- [7k] FIELD TANGGAL — Pre-filled dengan note_date dari $note --}}
            <div class="form-group">
                <label for="note_date" class="form-label">
                    <span class="label-icon">📅</span>
                    Tanggal <span class="required">*</span>
                </label>
                <input type="date"
                       id="note_date"
                       name="note_date"
                       class="form-input {{ $errors->has('note_date') ? 'is-error' : '' }}"
                       value="{{ old('note_date', $note->note_date->format('Y-m-d')) }}"
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
                       value="{{ old('hashtags', $note->hashtags) }}">
                <p class="form-subtitle" style="font-size: 0.75rem; margin-top: 4px;">Pisahkan dengan spasi untuk beberapa hashtag</p>
                @error('hashtags')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- [7l] FIELD ISI CATATAN — Quill RTE, pre-filled dengan $note->content --}}
            <div class="form-group">
                <label class="form-label">
                    <span class="label-icon">✍️</span>
                    Isi Catatan <span class="required">*</span>
                </label>

                {{-- [7l1] Container Quill Editor --}}
                <div id="quill-editor" class="quill-editor-container"></div>

                {{-- [7l2] Hidden input: menampung HTML dari Quill untuk dikirim ke Controller.
                           Nilai awal di-isi dari $note->content (STEP [3n]).
                           JavaScript mengisinya ulang sebelum submit (lihat STEP [7r]). --}}
                <input type="hidden"
                       name="content"
                       id="content-input"
                       value="{{ old('content', $note->content) }}">

                @error('content')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- [7m] Tombol aksi form --}}
            <div class="form-actions">
                <a href="{{ route('notes.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-primary" id="btn-submit-edit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                        <path d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

@endsection

{{-- ============================================================
     STEP [7n] — JavaScript section untuk Quill RTE (Edit Mode)
     Sama dengan create.blade.php (STEP [6L]), tetapi content
     Quill diisi dari data $note->content yang sudah ada.
============================================================ --}}
@push('scripts')

{{-- [7o] Load Quill.js dari CDN --}}
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    // ============================================================
    // [7p] Inisialisasi Quill Editor (sama dengan STEP [6n])
    // ============================================================
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Tulis isi catatanmu di sini...',
        modules: {
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],    // [7q] Tombol upload gambar
                    ['clean']
                ],
                handlers: {
                    image: imageUploadHandler // [7r] Handler upload gambar (sama dengan STEP [6q])
                }
            }
        }
    });

    // ============================================================
    // [7s] LOAD EXISTING CONTENT — Isi editor dengan konten yang sudah ada.
    //      Nilai diambil dari hidden input (#content-input) yang sudah
    //      diisi dari $note->content (STEP [7l2]).
    // ============================================================
    const contentInput = document.getElementById('content-input');
    const existingContent = contentInput.value;

    if (existingContent && existingContent.trim() !== '') {
        // [7t] Masukkan HTML konten lama ke Quill editor
        quill.root.innerHTML = existingContent;
    }

    // ============================================================
    // [7u] Fungsi upload gambar (sama persis dengan STEP [6r])
    //      Upload ke route notes.upload-image (STEP [4e])
    //      → NoteController@uploadImage (STEP [3w])
    // ============================================================
    function imageUploadHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = async () => {
            const file = input.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            try {
                // [7v] POST ke server (STEP [4e]) → controller (STEP [3w])
                const response = await fetch('{{ route('notes.upload-image') }}', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                // [7w] Insert gambar ke editor di posisi kursor
                const range = quill.getSelection();
                quill.insertEmbed(range ? range.index : 0, 'image', data.url);

            } catch (error) {
                alert('Gagal mengupload gambar. Coba lagi.');
            }
        };
    }

    // ============================================================
    // [7x] Sinkronisasi konten Quill ke hidden input sebelum submit
    //      (sama dengan STEP [6y]) → dikirim ke NoteController@update (STEP [3p])
    // ============================================================
    const form = document.getElementById('edit-note-form');

    form.addEventListener('submit', function() {
        // [7y] Ambil HTML dari Quill dan masukkan ke hidden input
        contentInput.value = quill.root.innerHTML;
    });
</script>
@endpush
