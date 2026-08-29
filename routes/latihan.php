<?php

use App\Http\Controllers\Student\AttemptController;
use App\Http\Controllers\Student\PracticeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('latihan')->name('latihan.')->group(function () {
    Route::get('/', [PracticeController::class, 'index'])->name('index');

    Route::post('soal/{problem}/mulai', [AttemptController::class, 'store'])->name('attempt.store');
    Route::get('percobaan/{attempt}', [AttemptController::class, 'show'])->name('attempt.show');
    Route::post('percobaan/{attempt}/lanjut', [AttemptController::class, 'advance'])->name('attempt.advance');
    Route::post('percobaan/{attempt}/selesai', [AttemptController::class, 'finish'])->name('attempt.finish');
});
