<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route untuk halaman Dashboard Utama

// ==========================================
// DASHBOARD ADMIN POKJA
// ==========================================
        Route::get('/dashboard-pokja', function () {
            return view('Admin_Pokja.dashboard_pokja');
        });

        // Route untuk halaman Ajukan Peminjaman
        Route::get('/dashboard-pokja/ajukan', function () {
            return view('Admin_Pokja.ajukan_peminjaman');
        });

        // Route untuk halaman Upload Arsip
        Route::get('/dashboard-pokja/upload', function () {
            return view('Admin_Pokja.upload_arsip_pokja');
        });

        // Route untuk halaman History
        Route::get('/dashboard-pokja/history', function () {
            return view('Admin_Pokja.history_pokja');
        });

        // Route untuk halaman Profil
        Route::get('/dashboard-pokja/profil', function () {
            return view('Admin_Pokja.profile_pokja');
        });

        // Route untuk halaman Form Peminjaman
        Route::get('/dashboard-pokja/form-peminjaman-pokja', function () {
            return view('Admin_Pokja.form_peminjaman_pokja');
        });

        // Route untuk halaman Form Upload
        Route::get('/dashboard-pokja/form-upload-pokja', function () {
            return view('Admin_Pokja.form_upload_pokja');
        });

        // Route untuk Riwayat Peminjaman Terakhir
        Route::get('/dashboard-pokja/riwayat-peminjaman-terakhir-pokja', function () {
            return view('Admin_Pokja.riwayat_peminjaman_terakhir_pokja');
        });

        // Route untuk Riwayat Arsip Terakhir
        Route::get('/dashboard-pokja/riwayat-arsip-terakhir-pokja', function () {
            return view('Admin_Pokja.riwayat_arsip_terakhir_pokja');
        });

        // Route untuk Peminjaman Aktif
        Route::get('/dashboard-pokja/peminjaman-aktif', function () {
            return view('Admin_Pokja.peminjaman_aktif_pokja');
        }); 

// ==========================================
// DASHBOARD KETUA POKJA
// ==========================================

        // Route untuk halaman Dashboard Ketua Pokja
        Route::get('/ketua-pokja', function () {
            return view('Ketua_Pokja.dashboard_ketuapokja');
        });

        // Route untuk halaman Ajukan Peminjaman Ketua Pokja
        Route::get('/ketua-pokja/ajukan', function () {
            return view('Ketua_Pokja.ajukan_peminjaman_ketuapokja');
        });

        // Route untuk halaman Upload Arsip Ketua Pokja
        Route::get('/ketua-pokja/upload', function () {
            return view('Ketua_Pokja.upload_arsip_ketuapokja');
        });

        Route::get('/ketua-pokja/validasi', function () {
            return view('Ketua_Pokja.validasi_upload_ketuapokja');
        });

        // Route untuk halaman History Ketua Pokja
        Route::get('/ketua-pokja/history', function () {
            return view('Ketua_Pokja.history_ketuapokja');
        });

        // Route untuk halaman Profil Ketua Pokja
        Route::get('/ketua-pokja/profil', function () {
            return view('Ketua_Pokja.profile_ketuapokja');
        }); 

        // Route untuk halaman Form Peminjaman Ketua Pokja
        Route::get('/ketua-pokja/form-peminjaman', function () {
            return view('Ketua_Pokja.form_peminjaman_ketuapokja');
        });

        // Route untuk halaman Form Upload Ketua Pokja
        Route::get('/ketua-pokja/form-upload', function () {
            return view('Ketua_Pokja.form_upload_ketuapokja');
        });

        // Route untuk halaman Peminjaman Aktif Ketua Pokja
        Route::get('/ketua-pokja/peminjaman-aktif', function () {
            return view('Ketua_Pokja.peminjaman_aktif_ketuapokja');
        });

        // Route untuk halaman Riwayat Arsip Terakhir Ketua Pokja
        Route::get('/ketua-pokja/riwayat-arsip-terakhir', function () {
            return view('Ketua_Pokja.riwayat_arsip_terakhir_ketuapokja');
        });

        // Route untuk halaman Riwayat Peminjaman Terakhir Ketua Pokja
        Route::get('/ketua-pokja/riwayat-peminjaman-terakhir', function () {
            return view('Ketua_Pokja.riwayat_peminjaman_terakhir_ketuapokja');
        });

// ==========================================
// DASHBOARD ADMIN TUBMN
// ==========================================

        Route::get('/admin-tu', function () {
            return view('Admin_TU.dashboard_admintu');
        });

        // Route untuk halaman validasi upload arsip admin TU
        Route::get('/admin-tu/validasi', function () {
            return view('Admin_TU.validasi_admintu');
        });

        // Route untuk halaman profile admin TU
        Route::get('/admin-tu/profil', function () {
            return view('Admin_TU.profile_admintu');
        });

        // Route untuk halaman laporan arsip admin TU
        Route::get('/admin-tu/laporan-arsip', function () {
            return view('Admin_TU.laporanarsip_admintu');
        });

// ==========================================
// DASHBOARD SUPER ADMIN TUBMN
// ==========================================

        Route::get('/super-admin', function () {
            return view('Super_Admin.dashboard_superadmin'); // Sesuaikan dengan nama file/folder Mas
        });

        Route::get('/super-admin/laporan', function () {
            return view('Super_Admin.laporan_arsip_superadmin'); // Sesuaikan dengan nama folder jika ada, misal: 'Super_Admin.laporan_arsip'
        });

        Route::get('/super-admin/kelola', function () {
            return view('Super_Admin.kelola_user_superadmin'); // Sesuaikan dengan nama file/folder Mas
        });

        Route::get('/super-admin/penghapusan', function () {
            return view('Super_Admin.penghapusan_arsip_superadmin'); // Sesuaikan dengan penamaan folder Mas
        });

        Route::get('/super-admin/validasi', function () {
            return view('Super_Admin.validasi_peminjaman_superadmin'); // Sesuaikan dengan penamaan folder Mas
        });

        Route::get('/super-admin/peminjaman-aktif', function () {
            return view('Super_Admin.peminjaman_aktif_superadmin'); // Sesuaikan dengan penamaan folder Mas
        });