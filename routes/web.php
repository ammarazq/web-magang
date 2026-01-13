<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UmurController;
use App\Http\Controllers\SarjanaController;
use App\Http\Controllers\MagisterController;
use App\Http\Controllers\DoktoralController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'doRegister'])->name('register.submit');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Memerlukan Login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/upload-dokumen', [AuthController::class, 'uploadDokumen'])->name('upload.dokumen');
});

// Homepage
Route::get('/', function () {
    return view('pages.home');
});

// About & Contact
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/gallery', function () {
    return view('pages.gallery');
})->name('gallery');

// Perkuliahan Routes
Route::get('/jadwal_TTM', function () {
    return view('pages.jadwal_ttm');
})->name('jadwal_ttm');

Route::get('/Jadwal_Tuton', function () {
    return view('pages.jadwal_tuton');
})->name('jadwal_tuton');

Route::get('/Jadwal_Ujian', function () {
    return view('pages.jadwal_ujian');
})->name('jadwal_ujian');

// Layanan Routes
Route::get('/layanan_daftar', function () {
    return view('pages.layanan_daftar');
})->name('layanan_daftar');

Route::get('/layanan_regis', function () {
    return view('pages.layanan_regis');
})->name('layanan_regis');

Route::get('/layanan_registrasi', function () {
    return view('layanan_registrasi');
})->name('layanan_registrasi');

Route::get('/layanan_TTM', function () {
    return view('pages.layanan_ttm');
})->name('layanan_ttm');

Route::get('/layanan_ujian', function () {
    return view('pages.layanan_ujian');
})->name('layanan_ujian');

Route::get('/layanan_TAPS', function () {
    return view('pages.layanan_taps');
})->name('layanan_taps');

Route::get('/layanan_wisuda', function () {
    return view('pages.layanan_wisuda');
})->name('layanan_wisuda');

// Fakultas Routes
Route::get('/FEB', function () {
    return view('pages.feb');
})->name('feb');

Route::get('/FKIP', function () {
    return view('pages.fkip');
})->name('fkip');

Route::get('/FISIP', function () {
    return view('pages.fisip');
})->name('fisip');

Route::get('/FAST', function () {
    return view('pages.fast');
})->name('fast');

Route::get('/Pascasarjana', function () {
    return view('pages.pascasarjana');
})->name('pascasarjana');

// Aplikasi Routes
Route::get('/Tutorial_Online', function () {
    return view('pages.tutorial_online');
})->name('tutorial_online');

Route::get('/Tutorial_Webinar', function () {
    return view('pages.tutorial_webinar');
})->name('tutorial_webinar');

Route::get('/Praktikum', function () {
    return view('pages.praktikum');
})->name('praktikum');

// Pendaftaran Routes (dari layanan_registrasi)
Route::get('/sarjana', [SarjanaController::class, 'show'])->name('sarjana');
Route::post('/sarjana/submit', [SarjanaController::class, 'submit'])->name('sarjana.submit');
Route::get('/sarjana/captcha/refresh', [SarjanaController::class, 'generateCaptcha'])->name('sarjana.captcha.refresh');

Route::get('/magister', [MagisterController::class, 'index'])->name('magister');
Route::post('/magister/submit', [MagisterController::class, 'submit'])->name('magister.submit');
Route::get('/magister/captcha/refresh', [MagisterController::class, 'generateCaptcha'])->name('magister.captcha.refresh');

Route::get('/doktoral', [DoktoralController::class, 'index'])->name('doktoral');
Route::post('/doktoral/submit', [DoktoralController::class, 'submit'])->name('doktoral.submit');
Route::get('/doktoral/captcha/refresh', [DoktoralController::class, 'generateCaptcha'])->name('doktoral.captcha.refresh');
