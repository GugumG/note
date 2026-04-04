# 📖 Dokumentasi Alur & Struktur NoteApp

Dokumen ini dibuat untuk membantu Anda memahami bagaimana aplikasi **NoteApp** bekerja, mulai dari struktur database hingga tampilan yang Anda lihat di browser.

---

## 🛠️ 1. Pondasi: Database (Migrations)
Aplikasi ini menggunakan **SQLite** sebagai database. Struktur tabel dibuat menggunakan file *Migration* di folder `database/migrations/`:
1.  **`notes`**: Menyimpan judul, konten (format HTML), tanggal catatan, status pin, dan hashtags.
2.  **`tasks`**: Menyimpan judul, deadline, status (pending/complete), konten, data penyelesaian, tanggal selesai, dan status pin.
3.  **Kolom Tambahan**: Kita telah menambahkan fitur **Pinning**, **Tracking Selesai**, dan **Hashtags** melalui migrasi tambahan untuk memperkaya fitur utama.

---

## 🧠 2. Otak Aplikasi: Models & Controllers

### Models (`app/Models/`)
Model adalah representasi data di kodingan kita.
*   **`Note.php`**: Mengatur data catatan.
*   **`Task.php`**: Mengatur data tugas. Di sini ada logika "Pintar" (Accessors) untuk mendeteksi apakah tugas sudah **"Mepet"** atau **"Telat"** berdasarkan tanggal hari ini.

### Controllers (`app/Http/Controllers/`)
Controller adalah pengatur lalu lintas data.
*   **`NoteController`**: Menangani pembuatan catatan, upload gambar ke server, pencarian berdasarkan hashtag, dan sistem pin.
*   **`TaskController`**: Menangani pembuatan tugas, perhitungan otomatis selisih hari saat tugas selesai, dan pengurutan prioritas.

---

## 🛤️ 3. Jalur Jalan: Routes (`routes/web.php`)
Setiap kali Anda mengetik URL atau mengklik tombol, Laravel melihat file `web.php`.
*   `/notes`: Memanggil `NoteController@index` untuk menampilkan semua catatan.
*   `/tasks`: Memanggil `TaskController@index` untuk menampilkan semua tugas.
*   `/toggle-pin`: Route khusus untuk mengubah status pin tanpa harus mengedit seluruh data.

---

## 🎨 4. Tampang Aplikasi: Views (`resources/views/`)
Menggunakan **Blade Templating** agar tampilan bisa dinamis.
*   **`layouts/app.blade.php`**: "Master Template" yang berisi Sidebar dan Navbar. Halaman lain tinggal "menumpang" di sini (concept `@extends`).
*   **Index**: Halaman utama yang menampilkan grid kartu. Menampilkan badge status, hashtag, dan tombol aksi.
*   **Create/Edit**: Form input menggunakan **Quill.js** untuk pengeditan teks kaya (bisa tebal, miring, gambar).

---

## 🚀 5. Alur Kerja Fitur (Contoh: Membuat Catatan)
1.  **User** klik "Tambah Catatan".
2.  **Route** mengarahkan ke form buat (`create.blade.php`).
3.  **User** mengisi judul & konten (pakai editor Quill).
4.  Saat **Submit**, data dikirim ke `NoteController@store`.
5.  **Controller** memvalidasi data dan menyimpannya ke **Database**.
6.  **User** diarahkan kembali ke halaman **Index**, dan catatan baru muncul paling atas jika di-pin.

---

## 💎 Fitur Khusus yang Perlu Diketahui:
*   **Auto-Pin Urgensi**: Sistem secara otomatis menaikkan tugas yang **"Telat"** atau **"Mepet"** ke posisi atas agar Anda tidak lupa.
*   **Smart Filter**: Kotak pencarian di dashboard bisa mencari berdasarkan hashtag (misal: `#ide`) secara instan.
*   **Vite Assets**: Aplikasi ini menggunakan **Vite** untuk mengolah CSS dan JS agar loadingnya super cepat dan modern.

---

> [!TIP]
> Jika Anda ingin menambah fitur baru, selalu mulai dari **Migration** (tambah kolom di DB), lalu **Model** (daftarkan kolom tersebut), baru kemudian ke **Controller** dan **View**.
