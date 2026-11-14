<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'landing'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('masuk');
    Route::post('/masuk', [AuthController::class, 'login'])->name('masuk.attempt');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('daftar');
    Route::post('/daftar', [AuthController::class, 'register'])->name('daftar.store');
});

Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/beranda', [DashboardController::class, 'index'])->name('beranda');

    // Moderasi: laporan, verifikasi & tolak materi (moderator + admin)
    Route::middleware('moderator')->prefix('moderasi')->name('moderasi.')->group(function () {
        Route::get('/laporan', [ModerationController::class, 'reports'])->name('laporan');
        Route::get('/laporan/{report}', [ModerationController::class, 'reportDetail'])->name('laporan.detail');
        Route::post('/laporan/{report}/proses', [ModerationController::class, 'reportProcess'])->name('laporan.proses');
        Route::post('/laporan/{report}/selesai', [ModerationController::class, 'reportFinish'])->name('laporan.selesai');
        Route::delete('/laporan/{report}/hapus', [ModerationController::class, 'reportDeleteContent'])->name('laporan.hapus');
        Route::post('/materi/{material}/verifikasi', [ModerationController::class, 'verifyMaterial'])->name('materi.verifikasi');
        Route::post('/materi/{material}/tolak', [ModerationController::class, 'rejectMaterial'])->name('materi.tolak');
    });

    // Verifikasi jawaban: dipindah ke auth biasa (pemilik pertanyaan atau moderator/admin fakultas sama)
    Route::post('/komentar/{comment}/verifikasi', [ModerationController::class, 'verifyAnswer'])->name('moderasi.komentar.verifikasi');
    Route::delete('/komentar/{comment}', [ModerationController::class, 'destroyComment'])->name('komentar.destroy');

    Route::post('/materi/{material}/lapor', [ReportController::class, 'storeMaterial'])->name('materi.lapor');
    Route::post('/komentar/{comment}/lapor', [ReportController::class, 'storeComment'])->name('komentar.lapor');
    Route::post('/komentar/{comment}/upvote', [ReportController::class, 'upvote'])->name('komentar.upvote');

    Route::get('/mata-kuliah', [CourseController::class, 'index'])->name('mata-kuliah');
    Route::get('/mata-kuliah/{course:slug}', [CourseController::class, 'show'])->name('mata-kuliah.detail');

    Route::get('/unggah', [MaterialController::class, 'create'])->name('unggah');
    Route::post('/unggah', [MaterialController::class, 'store'])->name('unggah.store');

    Route::get('/materi/{material}', [MaterialController::class, 'show'])->name('materi.detail');
    Route::get('/materi/{material}/unduh', [MaterialController::class, 'download'])->name('materi.unduh');
    Route::get('/materi/{material}/pratinjau', [MaterialController::class, 'preview'])->name('materi.pratinjau');
    Route::get('/materi/{material}/edit', [MaterialController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{material}', [MaterialController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{material}', [MaterialController::class, 'destroy'])->name('materi.destroy');
    Route::post('/materi/{material}/komentar', [MaterialController::class, 'comment'])->name('materi.komentar');

    Route::get('/kuis', [QuizController::class, 'index'])->name('kuis-saya');
    Route::get('/kuis/riwayat', [QuizController::class, 'index'])->name('riwayat');
    Route::get('/kuis/pilih', [QuizController::class, 'choose'])->name('kuis.pilih');
    Route::get('/kuis/bank-soal', [QuizController::class, 'createManual'])->name('kuis.bank-soal');
    Route::post('/kuis/bank-soal', [QuizController::class, 'storeManual'])->name('kuis.bank-soal.store');
    Route::post('/kuis/bank-soal/unggah', [QuizController::class, 'uploadBank'])->name('kuis.bank-soal.upload');
    Route::get('/kuis/bank-soal/template', [QuizController::class, 'downloadTemplate'])->name('kuis.bank-soal.template');
    Route::get('/kuis/ai', [QuizController::class, 'createAi'])->name('kuis.ai');
    Route::post('/kuis/ai/proses', [QuizController::class, 'processing'])->name('kuis.ai.proses');
    Route::post('/kuis/ai', [QuizController::class, 'generate'])->name('kuis.ai.generate');
    Route::get('/kuis/{quiz}/pratinjau', [QuizController::class, 'preview'])->name('kuis.preview');
    Route::post('/kuis/{quiz}/terbitkan', [QuizController::class, 'publish'])->name('kuis.publish');
    Route::get('/kuis/{quiz}/kerjakan', [QuizController::class, 'play'])->name('kuis.play');
    Route::post('/kuis/{quiz}/selesai', [QuizController::class, 'submit'])->name('kuis.submit');
    Route::delete('/kuis/{quiz}', [QuizController::class, 'destroy'])->name('kuis.destroy');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/mata-kuliah', [AdminController::class, 'courses'])->name('kelola');
        Route::post('/mata-kuliah', [AdminController::class, 'storeCourse'])->name('kelola.store');
        Route::put('/mata-kuliah/{course}', [AdminController::class, 'updateCourse'])->name('kelola.update');
        Route::delete('/mata-kuliah/{course}', [AdminController::class, 'destroyCourse'])->name('kelola.destroy');
        Route::get('/komentar', [AdminController::class, 'comments'])->name('moderasi');
        Route::post('/komentar/{comment}/setujui', [AdminController::class, 'approveComment'])->name('moderasi.setujui');
        Route::delete('/komentar/{comment}', [AdminController::class, 'destroyComment'])->name('moderasi.hapus');
        Route::get('/moderator', [AdminController::class, 'moderators'])->name('moderator');
        Route::post('/moderator/{user}/toggle', [AdminController::class, 'toggleModerator'])->name('moderator.toggle');
    });
});
