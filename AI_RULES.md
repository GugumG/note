# 🤖 AI Coding Rules & Standards (NoteApp)

Dokumen ini adalah panduan utama bagi AI Coding Assistant dalam membangun dan memodifikasi kode di project **NoteApp**. Aturan ini dibuat untuk memastikan kode tetap **bersih, sederhana, dan mudah dipahami** oleh manusia.

---

## 🎯 Prinsip Utama
1.  **KISS (Keep It Simple, Stupid)**: Jangan gunakan logika yang terlalu canggih atau abstrak jika cara sederhana sudah cukup.
2.  **Explicit over Implicit**: Lebih baik menulis kode yang jelas maksudnya daripada kode "pintar" yang sulit dibaca.
3.  **Educational Coding**: Setiap baris kode baru harus membantu USER belajar dan memahami alur aplikasi.

---

## 📝 Aturan Penulisan Kode & Komentar

### 1. Komentar Wajib (Mandatory Comments)
Setiap file, class, method, atau blok logika penting **WAJIB** memiliki komentar dalam Bahasa Indonesia.
*   **File Header**: Jelaskan fungsi file ini dan hubungannya dengan file lain (misal: Controller ini menghubungkan Model X ke View Y).
*   **Method/Fungsi**: Jelaskan apa inputnya, apa outputnya, dan apa tujuannya.
*   **Inline Comments**: Gunakan komentar untuk menjelaskan logika yang tidak langsung terlihat (kompleks).

### 2. Penamaan (Naming Convention)
*   **Variabel & Fungsi**: Gunakan Bahasa Inggris yang deskriptif (misal: `$isOverdue`, bukan `$telat`).
*   **Bahasa**: Logika menggunakan Bahasa Inggris (standar koding), namun penjelasan/komentar menggunakan Bahasa Indonesia.

### 3. Struktur Laravel & Clean Code
*   **Standard Patterns**: Gunakan fitur bawaan Laravel (Eloquent, Blade, Middleware) secara maksimal. Hindari membuat sistem kustom jika Laravel sudah menyediakannya.
*   **No Dead Code**: Jangan biarkan ada variabel, fungsi, atau file yang tidak terpakai (Unused Code). Hapus segera jika tidak dibutuhkan lagi.

---

## 🛠️ Stack Teknologi (Strict)
*   **Framework**: Laravel (LTS/Latest).
*   **CSS**: Tailwind CSS (Utamakan Utility Classes).
*   **Database**: SQLite.
*   **Frontend**: Blade + Vanilla JS (minimalis).

---

## 🔒 Aturan Integritas Data
1. **No Data Loss**: Data yang sudah di-input jangan sampai terhapus walaupun kode harus mengubah struktur datanya.
2. **Pengecualian**: Kecuali data bentrok (conflict) atau kolomnya memang dihapus secara sengaja. 
3. **Pencadangan (Backup)**: Jika harus menghapus data karena poin di atas, **WAJIB** backup data tersebut terlebih dahulu (misal lewat database seeding atau export script).

---

## 📋 Prosedur AI Saat Bekerja
1.  **Baca dulu**: Selalu baca file ini di awal sesi untuk menyelaraskan gaya penulisan.
2.  **Berikan Opsi**: Jika ada solusi yang lebih efisien tapi kompleks, tawarkan dulu sebelum menulis kode.
3.  **Review Diri**: Sebelum menyerahkan kode, pastikan komentar sudah lengkap dan kode sudah rapi.

---

> [!NOTE]
> File ini adalah kontrak antara USER dan AI. Jika AI melanggar aturan ini, USER berhak meminta revisi total.
