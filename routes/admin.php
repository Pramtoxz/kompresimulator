<?php

use App\Http\Controllers\Admin\ProblemController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('mahasiswa', [StudentController::class, 'index'])->name('students.index');
    Route::get('mahasiswa/baru', [StudentController::class, 'create'])->name('students.create');
    Route::post('mahasiswa', [StudentController::class, 'store'])->name('students.store');
    Route::get('mahasiswa/{student}/ubah', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('mahasiswa/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('mahasiswa/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('mahasiswa/{student}/soal', [ProblemController::class, 'index'])->name('students.problems.index');
    Route::post('mahasiswa/{student}/soal', [ProblemController::class, 'store'])->name('students.problems.store');

    Route::get('soal/{problem}', [ProblemController::class, 'show'])->name('problems.show');
    Route::delete('soal/{problem}', [ProblemController::class, 'destroy'])->name('problems.destroy');
});
