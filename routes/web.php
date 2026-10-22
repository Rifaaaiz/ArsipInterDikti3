<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPokjaController;
use App\Http\Controllers\KetuaPokjaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeminjamanApprovalController;
use App\Http\Controllers\SuperadminDashboardController;
use App\Http\Controllers\SuperadminPeminjamanController;
use App\Http\Controllers\SuperadminSuratMasukController;
use App\Http\Controllers\SuperadminSuratKeluarController;
use App\Http\Controllers\SuperadminArsipVitalController;

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

        Route::get('/', function () {
            return view('login');
        });

        // 1. Menampilkan halaman login
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

        // 2. Memproses data dari form login
        Route::post('/login', [AuthController::class, 'login']);

// ==========================================
// DASHBOARD ADMIN POKJA
// ==========================================


    Route::middleware(['auth', 'role:admin_pokja'])->group(function () {

        Route::get('/dashboard-pokja',[AdminPokjaController::class, 'dashboard']);

        Route::get(
            '/dashboard-pokja/ajukan',
            [AdminPokjaController::class, 'ajukan']
        )->name('peminjaman.ajukan');

        // Route untuk halaman Upload Arsip (Utama)
        Route::get('/dashboard-pokja/upload', [AdminPokjaController::class, 'upload']);

        Route::get(
            '/dashboard-pokja/seluruh-arsip',
            [AdminPokjaController::class, 'seluruhArsip']
        )->name('arsip.seluruh');
        Route::get(
            '/dashboard-pokja/seluruh-arsip/{arsipId}/metadata',
            [AdminPokjaController::class, 'metadataSeluruhArsip']
        )->name('arsip.seluruh.metadata');

        // Route untuk halaman History
        Route::get(
            '/dashboard-pokja/history',
            [AdminPokjaController::class, 'history']
        )->name('pokja.history');

        // Route untuk halaman Profil
        Route::get('/dashboard-pokja/profil', [AdminPokjaController::class, 'profil']);

        Route::get(
            '/dashboard-pokja/form-peminjaman-pokja',
            [AdminPokjaController::class, 'formPeminjaman']
        )->name('peminjaman.form');

        Route::post(
            '/dashboard-pokja/form-peminjaman-pokja',
            [AdminPokjaController::class, 'storePeminjaman']
        )->name('peminjaman.store');

        // Route untuk halaman Form Upload (Isian Form)
        // Route untuk halaman Upload Arsip (Utama)
        Route::get(
            '/dashboard-pokja/upload',
            [AdminPokjaController::class, 'upload']
        )->name('arsip.index');

        // ... route lain tetap sama ...

        // Route untuk halaman Form Upload (Isian Form)
        Route::get(
            '/dashboard-pokja/form-upload-pokja',
            [AdminPokjaController::class, 'formUpload']
        )->name('arsip.form');

        // Route untuk memproses submit form Upload Arsip
        Route::post(
            '/dashboard-pokja/form-upload-pokja',
            [AdminPokjaController::class, 'storeArsip']
        )->name('arsip.store');

        // Route untuk Riwayat Peminjaman Terakhir
        Route::get(
            '/dashboard-pokja/riwayat-peminjaman-terakhir-pokja',
            [AdminPokjaController::class, 'riwayatPeminjamanTerakhir']
        )->name('peminjaman.riwayat');

        // Route untuk Riwayat Arsip Terakhir
        Route::get(
            '/dashboard-pokja/riwayat-arsip-terakhir-pokja',
            [AdminPokjaController::class, 'riwayatArsipTerakhir']
        )->name('arsip.riwayat');

        // Route untuk Peminjaman Aktif
        Route::get('/dashboard-pokja/peminjaman-aktif', [AdminPokjaController::class, 'peminjamanAktif'])
            ->name('pokja.peminjaman.aktif');
        Route::get('/dashboard-pokja/peminjaman-aktif/{peminjaman}', [AdminPokjaController::class, 'detailPeminjamanAktif'])
            ->name('pokja.peminjaman.aktif.detail');
        Route::get('/dashboard-pokja/peminjaman-aktif/{peminjaman}/balasan/file', [AdminPokjaController::class, 'showPeminjamanReplyFile'])
            ->name('pokja.peminjaman.aktif.balasan.file');

        Route::get('/dashboard-pokja/surat-masuk', [AdminPokjaController::class, 'suratMasuk'])
            ->name('pokja.surat-masuk');

        Route::get('/dashboard-pokja/surat-keluar', [AdminPokjaController::class, 'suratKeluar'])
            ->name('pokja.surat-keluar');

    });

    Route::middleware('auth')->post(
        '/logout',
        [AuthController::class, 'logout']
    )->name('logout');

// ==========================================
// DASHBOARD KETUA POKJA
// ==========================================

    Route::middleware(['auth', 'role:ketua_pokja'])->prefix('ketua-pokja')->group(function () {
        Route::get('/', [KetuaPokjaController::class, 'dashboard'])
            ->name('ketua.dashboard');

        Route::get('/ajukan', [KetuaPokjaController::class, 'ajukan'])
            ->name('ketua.ajukan');

        Route::get('/upload', [KetuaPokjaController::class, 'upload'])
            ->name('ketua.upload');

        Route::get('/validasi', [PeminjamanApprovalController::class, 'ketuaIndex'])
            ->name('ketua.peminjaman.validasi');

        Route::post('/validasi/terima-semua', [PeminjamanApprovalController::class, 'ketuaApproveAll'])
            ->name('ketua.peminjaman.approve-all');

        Route::patch('/validasi/{peminjaman}', [PeminjamanApprovalController::class, 'ketuaDecision'])
            ->name('ketua.peminjaman.decision');

        Route::get('/history', [KetuaPokjaController::class, 'history'])
            ->name('ketua.history');

        Route::get('/seluruh-arsip', [AdminPokjaController::class, 'seluruhArsip'])
            ->name('ketua.arsip.seluruh');
        Route::get('/seluruh-arsip/{arsipId}/metadata', [AdminPokjaController::class, 'metadataSeluruhArsip'])
            ->name('ketua.arsip.seluruh.metadata');

        Route::get('/profil', function () {
            return view('Ketua_Pokja.profile_ketuapokja');
        });

        Route::get('/form-peminjaman', [AdminPokjaController::class, 'formPeminjaman'])
            ->name('ketua.peminjaman.form');

        Route::post('/form-peminjaman', [AdminPokjaController::class, 'storePeminjaman'])
            ->name('ketua.peminjaman.store');

        Route::get('/form-upload', [AdminPokjaController::class, 'formUpload'])
            ->name('ketua.arsip.form');

        Route::post('/form-upload', [AdminPokjaController::class, 'storeArsip'])
            ->name('ketua.arsip.store');

        Route::get('/peminjaman-aktif', [KetuaPokjaController::class, 'peminjamanAktif'])
            ->name('ketua.peminjaman.aktif');

        Route::get('/riwayat-arsip-terakhir', [KetuaPokjaController::class, 'riwayatArsipTerakhir'])
            ->name('ketua.riwayat.arsip');

        Route::get('/riwayat-peminjaman-terakhir', [KetuaPokjaController::class, 'riwayatPeminjamanTerakhir'])
            ->name('ketua.riwayat.peminjaman');
    });

// ==========================================
// DASHBOARD ADMIN TUBMN
// ==========================================

    Route::middleware(['auth', 'role:admin_tu'])->prefix('admin-tu')->group(function () {
        Route::get('/', [PeminjamanApprovalController::class, 'tuDashboard'])
            ->name('admin-tu.dashboard');

        Route::get('/arsip/tambah', [PeminjamanApprovalController::class, 'tuArchiveForm'])
            ->name('admin-tu.arsip.form');

        Route::post('/arsip/tambah', [PeminjamanApprovalController::class, 'storeTuArchive'])
            ->name('admin-tu.arsip.store');

        // Route untuk halaman validasi upload arsip admin TU
        Route::get('/validasi', [PeminjamanApprovalController::class, 'tuIndex'])
            ->name('admin-tu.peminjaman.validasi');

        Route::patch('/validasi/{peminjaman}', [PeminjamanApprovalController::class, 'tuDecision'])
            ->name('admin-tu.peminjaman.decision');

        Route::get('/peminjaman-aktif', [PeminjamanApprovalController::class, 'tuActiveLoans'])
            ->name('admin-tu.peminjaman.aktif');

        // Route untuk halaman profile admin TU
        Route::get('/profil', function () {
            return view('Admin_TU.profile_admintu');
        });

        // Route untuk halaman laporan arsip admin TU
        Route::get('/laporan-arsip', [PeminjamanApprovalController::class, 'tuArchiveReport'])
            ->name('admin-tu.laporan.arsip');
    });

// ==========================================
// DASHBOARD SUPER ADMIN TUBMN
// ==========================================

    Route::middleware(['auth', 'role:superadmin'])->prefix('super-admin')->group(function () {
        Route::get('/', [SuperadminDashboardController::class, 'dashboard'])
            ->name('superadmin.dashboard');

        Route::get('/arsip/tambah', [SuperadminDashboardController::class, 'createArchive'])
            ->name('superadmin.arsip.form');

        Route::post('/arsip/tambah', [SuperadminDashboardController::class, 'storeArchive'])
            ->name('superadmin.arsip.store');

        Route::get('/arsip-vital/aktif', [SuperadminArsipVitalController::class, 'index'])
            ->name('superadmin.arsip-vital.aktif');

        Route::get('/arsip-vital/inaktif', [SuperadminArsipVitalController::class, 'indexInactive'])
            ->name('superadmin.arsip-vital.inaktif');

        Route::get('/arsip-vital/tambah', [SuperadminArsipVitalController::class, 'create'])
            ->name('superadmin.arsip-vital.create');

        Route::post('/arsip-vital/tambah', [SuperadminArsipVitalController::class, 'store'])
            ->name('superadmin.arsip-vital.store');

        Route::get('/arsip-vital/{arsipVital}/lihat', [SuperadminArsipVitalController::class, 'show'])
            ->name('superadmin.arsip-vital.show');

        Route::delete('/arsip-vital/{arsipVital}', [SuperadminArsipVitalController::class, 'deleteInactive'])
            ->name('superadmin.arsip-vital.delete-inaktif');

        Route::get('/peminjaman/buat', [SuperadminPeminjamanController::class, 'create'])
            ->name('superadmin.peminjaman.create');

        Route::post('/peminjaman/buat', [SuperadminPeminjamanController::class, 'store'])
            ->name('superadmin.peminjaman.store');

        Route::get('/pokja/{pokjaSlug}/surat-keluar', [SuperadminSuratKeluarController::class, 'index'])
            ->name('superadmin.surat-keluar');

        Route::get('/pokja/adia/surat-masuk', [SuperadminSuratMasukController::class, 'indexAdia'])
            ->name('superadmin.surat-masuk.adia');

        Route::get('/pokja/{pokjaSlug}/surat-masuk', [SuperadminSuratMasukController::class, 'index'])
            ->name('superadmin.surat-masuk');

        Route::get('/pokja/{pokjaSlug}/surat-masuk/peminjaman/{peminjaman}/file', [SuperadminSuratMasukController::class, 'showPeminjamanFile'])
            ->name('superadmin.surat-masuk.peminjaman.file');

        Route::get('/pokja/{pokjaSlug}/surat-masuk/peminjaman/{peminjaman}/balas', [SuperadminSuratMasukController::class, 'replyPeminjamanForm'])
            ->name('superadmin.surat-masuk.peminjaman.balas');

        Route::post('/pokja/{pokjaSlug}/surat-masuk/peminjaman/{peminjaman}/balas', [SuperadminSuratMasukController::class, 'storePeminjamanReply'])
            ->name('superadmin.surat-masuk.peminjaman.balas.store');

        Route::get('/pokja/{pokjaSlug}/surat-masuk/peminjaman/{peminjaman}', [SuperadminSuratMasukController::class, 'showPeminjaman'])
            ->name('superadmin.surat-masuk.peminjaman.detail');

        Route::get('/pokja/{pokjaSlug}/surat-masuk/{arsip}/balas', [SuperadminSuratMasukController::class, 'replyForm'])
            ->name('superadmin.surat-masuk.balas');

        Route::post('/pokja/{pokjaSlug}/surat-masuk/{arsip}/balas', [SuperadminSuratMasukController::class, 'storeReply'])
            ->name('superadmin.surat-masuk.balas.store');

        Route::get('/pokja/{pokjaSlug}/surat-masuk/{arsip}', [SuperadminSuratMasukController::class, 'show'])
            ->name('superadmin.surat-masuk.detail');

        Route::get('/pokja/{pokjaSlug}/surat-masuk/{arsip}/file', [SuperadminSuratMasukController::class, 'showFile'])
            ->name('superadmin.surat-masuk.file');

        Route::get('/arsip/{arsip}/edit', [SuperadminDashboardController::class, 'editArchive'])
            ->name('superadmin.arsip.edit');

        Route::put('/arsip/{arsip}/edit', [SuperadminDashboardController::class, 'updateArchive'])
            ->name('superadmin.arsip.update');

        Route::delete('/arsip/{arsip}/file', [SuperadminDashboardController::class, 'deleteArchiveFile'])
            ->name('superadmin.arsip.file.delete');

        Route::get('/laporan', [SuperadminDashboardController::class, 'archiveReport'])
            ->name('superadmin.arsip.laporan');

        Route::get('/kelola', [SuperadminDashboardController::class, 'users'])
            ->name('superadmin.users');

        Route::post('/kelola', [SuperadminDashboardController::class, 'storeUser'])
            ->name('superadmin.users.store');

        Route::delete('/kelola/{user}', [SuperadminDashboardController::class, 'deleteUser'])
            ->name('superadmin.users.delete');

        Route::get('/penghapusan', [SuperadminDashboardController::class, 'deletionArchive'])
            ->name('superadmin.arsip.penghapusan');

        Route::delete('/penghapusan/{arsip}', [SuperadminDashboardController::class, 'deleteArchive'])
            ->name('superadmin.arsip.delete');

        Route::get('/validasi', [PeminjamanApprovalController::class, 'tuIndex'])
            ->name('superadmin.peminjaman.validasi');

        Route::patch('/validasi/{peminjaman}', [PeminjamanApprovalController::class, 'tuDecision'])
            ->name('superadmin.peminjaman.decision');

        Route::get('/peminjaman-aktif', [SuperadminDashboardController::class, 'activeLoans'])
            ->name('superadmin.peminjaman.aktif');

        Route::post('/peminjaman-aktif/{peminjaman}/balasan', [SuperadminPeminjamanController::class, 'storeReply'])
            ->name('superadmin.peminjaman.balasan.store');
});
