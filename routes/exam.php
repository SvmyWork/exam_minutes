<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;

// add by svmy
Route::get('/exam/login', function () { return view('teacher.exam.login'); })->name('exam.login');
Route::get('/exam/instructions', function () { return view('teacher.exam.instructions'); })->name('exam.instructions');
Route::get('/exam/instructions-2', function () { return view('teacher.exam.extra_instructions'); })->name('exam.instructions-2');
Route::get('/exam/paper/{paperid}', [ExamController::class, 'start_exam'])->name('exam.paper');
Route::get('/exam/end', [ExamController::class, 'exit_exam'])->name('exam.end');