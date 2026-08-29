<?php

use App\Http\Controllers\Student\AttemptController;
use App\Http\Controllers\Student\PracticeController;
use App\Http\Controllers\Student\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('latihan')->name('latihan.')->group(function () {
    Route::get('/', [PracticeController::class, 'index'])->name('index');

    Route::post('soal/{problem}/mulai', [AttemptController::class, 'store'])->name('attempt.store');

    Route::middleware('attempt.owner')->group(function () {
        Route::get('percobaan/{attempt}', [AttemptController::class, 'show'])->name('attempt.show');
        Route::post('percobaan/{attempt}/lanjut', [AttemptController::class, 'advance'])->name('attempt.advance');
        Route::post('percobaan/{attempt}/selesai', [AttemptController::class, 'finish'])->name('attempt.finish');

        Route::get('percobaan/{attempt}/ruang-kerja', [WorkspaceController::class, 'show'])->name('attempt.workspace');
        Route::post('percobaan/{attempt}/berkas', [WorkspaceController::class, 'saveFile'])->name('attempt.file');
        Route::post('percobaan/{attempt}/migrasi', [WorkspaceController::class, 'runMigration'])->name('attempt.migration');
        Route::post('percobaan/{attempt}/baris', [WorkspaceController::class, 'storeRow'])->name('attempt.row');
    });
});
