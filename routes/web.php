<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/layanan_registrasi', function () {
    return view('layanan_registrasi');
});


Route::get('/layanan_ujian', function () {
    return view('pages.layanan_ujian');
})->name('layanan_ujian');


