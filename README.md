<div align="center">

# 🎓 STUDORA

### Platform Berbagi Materi & Belajar Bersama Mahasiswa Universitas Brawijaya

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![SDG 4](https://img.shields.io/badge/SDG_4-Quality_Education-C5192D?style=for-the-badge)](https://sdgs.un.org/goals/goal4)
[![Tests](https://img.shields.io/badge/PHPUnit-12_Passed_(59_Assertions)-brightgreen?style=for-the-badge&logo=githubactions&logoColor=white)](#-pengujian-automated-tests)

<p align="center">
  <i>"Yuk, rapikan belajar bareng STUDORA. Teman belajar anak Universitas Brawijaya."</i>
</p>

</div>

---

## 📖 Tentang Studora

**Studora** adalah platform pembelajaran kolaboratif yang dirancang khusus untuk mahasiswa **Universitas Brawijaya (UB)** dalam rangka mendukung pencapaian **SDG 4: Quality Education (Pendidikan Berkualitas)**. Platform ini memfasilitasi pertukaran materi kuliah berkualitas, diskusi akademik terstruktur dengan verifikasi jawaban, serta latihan kuis interaktif yang didukung integrasi kecerdasan buatan (AI).

Studora memastikan seluruh konten akademik yang beredar aman, terkurasi, dan relevan melalui sistem moderasi bertingkat berbasis yurisdiksi fakultas dan kontrol ketat hak cipta konten.

---

## ✨ Fitur-Fitur Utama

### 1. 📚 Manajemen Materi Kuliah
- **Unggah & Simpan Aman**: Mendukung format file dokumen perkuliahan (`.pdf`, `.docx`, `.pptx` hingga 5 MB) dengan penyimpanan di storage privat aman.
- **Unduh Terproteksi**: File hanya dapat diunduh oleh pengguna terdaftar dengan nama file asli (`Storage::download`).
- **Kurasi & Validasi Ulang (BR-10)**: Setiap perubahan pada materi yang telah diverifikasi/ditolak akan otomatis mengembalikan status menjadi `unverified` untuk divalidasi ulang oleh tim moderator.
- **Filter Fakultas & Status**: Mempermudah pencarian materi berdasarkan fakultas mahasiswa UB dan filter cepat untuk hanya menampilkan materi yang telah terverifikasi.

### 2. 💬 Diskusi Akademik & Tanya Jawab Terstruktur
- **Thread Komentar & Balasan**: Diskusi bertingkat yang rapi untuk setiap materi kuliah.
- **Upvoting Transparan**: Fitur apresiasi jawaban dengan proteksi anti-upvote komentar sendiri (BR-12) dan konsistensi counter berbasis transaksi database.
- **Jawaban Terverifikasi (BR-13)**: Pemilik pertanyaan (mahasiswa yang bertanya) maupun moderator/admin dapat menandai satu jawaban paling tepat sebagai *Jawaban Terverifikasi*.
- **Salin Ringkasan untuk Konfirmasi Dosen**: Tombol satu-klik untuk menyalin ringkasan thread tanya jawab ke clipboard agar mahasiswa mudah berkonsultasi kepada dosen pengampu.
- **Penghapusan Cepat (FR-19)**: Moderator dan admin dapat langsung menghapus komentar yang melanggar aturan dari halaman materi.

### 3. 🛡️ Sistem Moderasi Berbasis Fakultas & Panel Admin
- **Role-Based Access Control**: Pembagian peran yang tegas antara `student`, `moderator`, dan `admin`.
- **Yurisdiksi Fakultas**: Moderator hanya memiliki wewenang untuk memeriksa, memverifikasi, atau menolak materi dan laporan yang berasal dari fakultas yang sama dengan fakultas akunnya.
- **Pencegahan Konflik Kepentingan (BR-11)**: Moderator tidak diizinkan memverifikasi atau menolak materi yang diunggah oleh dirinya sendiri.
- **Sistem Pelaporan Lengkap**: Pelaporan materi maupun komentar bermasalah dengan pencegahan laporan ganda aktif dan cegah lapor konten sendiri.
- **Integritas Relasi Data (BR-03)**: Mata kuliah yang masih memiliki materi terlindungi dari penghapusan tidak disengaja.

### 4. 🧠 Kuis Interaktif & AI Quiz Generator
- **Kuis Pilihan Ganda**: Latihan mandiri dengan penghitungan skor dan pembahasan instan.
- **AI Quiz Generator**: Generator kuis bertenaga AI lokal (OmniRoute / LLM Gateway) untuk menghasilkan butir-butir soal pilihan ganda berkualitas tinggi berbahasa Indonesia langsung dari ringkasan materi.
- **Sistem Draft Kuis**: Kuis yang masih berstatus draft hanya dapat diakses dan diedit oleh pembuatnya sebelum diterbitkan ke publik.

---

## 🛠️ Arsitektur & Teknologi

| Komponen | Teknologi | Deskripsi |
| :--- | :--- | :--- |
| **Backend** | Laravel 11.x | Arsitektur MVC, Eloquent ORM, Form Request, Policies |
| **Frontend** | Blade & Tailwind CSS v4 | Desain responsif, clean UI dengan token warna brand Studora |
| **Typography** | Plus Jakarta Sans & DM Sans | Tipografi lokal mandiri berkecepatan tinggi tanpa CDN eksternal |
| **Database** | SQLite / MySQL | Skema relasional teroptimasi dengan foreign key constraints |
| **AI Gateway** | OmniRoute | Local gateway AI aman (127.0.0.1) untuk pembuatan kuis otomatis |
| **Testing** | PHPUnit | Automated Feature & Unit testing untuk seluruh aturan bisnis |

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek Studora di komputer lokal Anda:

### 1. Prasyarat Sistem
- **PHP** >= 8.2 (dengan ekstensi `pdo_sqlite`, `fileinfo`, `mbstring`, `curl`, `openssl`)
- **Composer** >= 2.x
- **Node.js** >= 18.x & **npm**

### 2. Kloning Repositori
```bash
git clone https://github.com/kahfiiii/Praktikum-WEB.git studora
cd studora
```

### 3. Pengaturan Dependensi & Konfigurasi Lingkungan
Salin file konfigurasi lingkungan dan pasang seluruh dependensi backend:
```bash
cp .env.example .env
composer install
```

Generate application key Laravel:
```bash
php artisan key:generate
```

### 4. Kompilasi Aset Frontend
Pasang modul node dan bangun aset CSS & JS:
```bash
npm install
npm run build
```

### 5. Migrasi Database & Seeder
Buat database SQLite kosong (jika belum ada) dan jalankan migrasi beserta data seeder awal:
```bash
# Pada Windows PowerShell
if (!(Test-Path "database/database.sqlite")) { New-Item -ItemType File -Path "database/database.sqlite" }

# Jalankan migrasi dan seeder
php artisan migrate:fresh --seed
```

### 6. Hubungkan Storage Link
```bash
php artisan storage:link
```

### 7. Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui browser di: **`http://127.0.0.1:8000`**

---

## 👥 Akun Demo untuk Pengujian

Database seeder telah menyediakan beberapa akun bawaan untuk pengujian alur hak akses:

| Peran (Role) | Email | Kata Sandi | Fakultas | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | `admin@student.ub.ac.id` | `password` | FILKOM | Hak akses penuh seluruh fakultas & panel admin |
| **Moderator FILKOM** | `moderator@student.ub.ac.id` | `password` | FILKOM | Moderasi materi & laporan wilayah FILKOM |
| **Mahasiswa** | `mahasiswa@student.ub.ac.id` | `password` | FILKOM | Pengguna reguler (unggah materi, kuis, diskusi) |

---

## 🧪 Pengujian (Automated Tests)

Studora dilengkapi dengan rangkaian **Feature Tests** yang komprehensif untuk memvalidasi seluruh aturan bisnis dan keamanan data:
- `BR-02`: Otorisasi edit dan hapus materi serta kuis draft hanya untuk pemilik.
- `BR-03`: Proteksi integritas mata kuliah yang memiliki materi.
- `BR-09`: Pembatasan verifikasi hanya untuk moderator dan admin.
- `BR-10`: Reset otomatis status verifikasi saat materi diubah/file diganti.
- `BR-11`: Larangan moderator memverifikasi atau menolak materinya sendiri.
- `BR-12`: Pencegahan upvote komentar milik sendiri dan konsistensi counter.
- `BR-13`: Hak verifikasi jawaban oleh penanya/moderator dan aturan tunggal jawaban terverifikasi.
- `Moderator Yurisdiksi`: Penolakan aksi moderasi lintas fakultas.
- `Pelaporan Konten`: Penolakan laporan duplikat dan pencegahan lapor konten sendiri.
- `Keamanan Unduhan`: Proteksi autentikasi unduhan dokumen materi.

Jalankan test suite dengan perintah:
```bash
php artisan test
```

Hasil eksekusi:
```text
   PASS  Tests\Unit\ExampleTest
   PASS  Tests\Feature\ExampleTest
   PASS  Tests\Feature\StudoraBusinessRulesTest
  ✓ br02 user lain tidak bisa edit atau hapus materi dan kuis draft
  ✓ br03 admin tidak bisa hapus mata kuliah yang memiliki materi
  ✓ br09 mahasiswa biasa tidak bisa verifikasi materi
  ✓ br10 edit materi verified atau rejected mereset status ke unverified
  ✓ br11 moderator tidak bisa verifikasi atau tolak materi sendiri
  ✓ br12 tolak upvote komentar sendiri dan jumlah upvote konsisten
  ✓ br13 verifikasi jawaban oleh penanya dan hanya satu terverifikasi
  ✓ moderator beda fakultas ditolak
  ✓ laporan ganda dan lapor konten sendiri ditolak
  ✓ unduh materi perlu login

  Tests:    12 passed (59 assertions)
```

---

## 🤝 Kontribusi

Proyek ini dikembangkan oleh tim mahasiswa **Fakultas Ilmu Komputer (FILKOM), Universitas Brawijaya**:
- **Mohamad Sohibul Kahfi** ([@kahfiiii](https://github.com/kahfiiii))
- **Andrian Indyanto** ([@indyanto07](https://github.com/indyanto07))

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah lisensi terbuka [MIT License](LICENSE).
