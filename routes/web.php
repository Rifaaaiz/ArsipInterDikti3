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
Route::get('/admin-pokja', function () {
    return view('Admin_Pokja.admin_pokja'); 
});

// Route untuk halaman Ajukan Peminjaman
Route::get('/admin-pokja/ajukan', function () {
    return view('Admin_Pokja.ajukan_peminjaman');
});

Route::get('/admin-pokja/upload', function () {
    return view('Admin_Pokja.upload_arsip');
});

// Route ke halaman History
Route::get('/admin-pokja/history', function () {
    return view('Admin_Pokja.history_pokja');
});

// Route untuk halaman profil
Route::get('/admin-pokja/profil', function () {
    return view('Admin_Pokja.profile_pokja');
});

// Route untuk halaman form peminjaman
Route::get('/admin-pokja/form-peminjaman-pokja', function () {
    return view('Admin_Pokja.form_peminjaman_pokja');
});

// Route untuk halaman form upload
Route::get('/admin-pokja/form-upload-pokja', function () {
    return view('Admin_Pokja.form_upload_pokja');
});