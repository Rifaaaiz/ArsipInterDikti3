# 📚 SIMARSIP (Sistem Informasi Manajemen Arsip dan Peminjaman Dokumen)

SIMARSIP adalah aplikasi berbasis web yang dirancang untuk mendigitalisasi pengelolaan arsip, melacak riwayat peminjaman dokumen, dan memfasilitasi sistem validasi berjenjang di lingkungan instansi (khususnya LLDikti Wilayah III). Sistem ini mengusung arsitektur *multi-role* untuk memastikan keamanan dan ketertiban tata kelola dokumen.

## ✨ Fitur Utama

Sistem ini mendukung 4 peran (*role*) utama dengan hak akses yang terstruktur:

*   👑 **Super Admin**: Mengelola data master, manajemen *user* (tambah/edit/hapus), mengelola penghapusan arsip, serta memantau laporan global sistem.
*   🏢 **Admin TU (Tata Usaha)**: Memvalidasi pengajuan peminjaman arsip, melihat laporan arsip, dan mengelola sirkulasi dokumen.
*   👔 **Ketua Pokja (Kelompok Kerja)**: Memantau arsip di lingkup Pokja-nya dan mengawasi aktivitas peminjaman.
*   💼 **Admin Pokja**: Mengunggah (*upload*) file arsip baru sesuai jenis/kategori, mengajukan peminjaman dokumen, dan melihat riwayat peminjaman aktif.

### Modul Inti:
*   **Klasifikasi Arsip Dinamis**: Manajemen dokumen terstruktur (terintegrasi dengan data Jadwal Retensi Arsip / JRA LLDikti melalui tabel `jenis_surats` / `klasifikasi_arsips`).
*   **Sirkulasi & Validasi**: Alur peminjaman dokumen dengan persetujuan (approval) yang tercatat otomatis di *database*.
*   **Pelaporan Komprehensif**: *Generate* laporan arsip dengan filter dinamis (berdasarkan nama, sumber, tanggal, kategori, dan kelompok kerja).
*   **Keamanan Terpusat**: Menggunakan *Custom Authentication*, pengelolaan sesi yang ketat, dan enkripsi *password* bawaan Laravel.

## 🚀 Teknologi yang Digunakan

*   **Backend:** [Laravel](https://laravel.com/) (PHP)
*   **Frontend:** [Tailwind CSS](https://tailwindcss.com/) & Laravel Blade
*   **Database:** MySQL / MariaDB
*   **Asset/Icons:** FontAwesome

## 🛠️ Panduan Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan SIMARSIP di perangkat Anda:

1. **Clone Repository**
   ```bash
   git clone [https://github.com/username-anda/simarsip.git](https://github.com/username-anda/simarsip.git)
   cd simarsip
