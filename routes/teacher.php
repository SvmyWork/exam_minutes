<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Middleware\TeachersMiddleware;

// Added by Ankan (Start)
Route::get('/teacher-login', [TeacherController::class, 'teacher_login'])->name('teacher.login');
Route::get('/teacher-register', [TeacherController::class, 'teacher_register'])->name('teacher.register');
Route::get('/teacher-forgot', [TeacherController::class, 'teacher_forgot'])->name('teacher.forgot');
// Added by Ankan (End)

Route::middleware(['web', TeachersMiddleware::class])->group(function () {
    // Define a route for the teacher (added by svmy)
    Route::get('/teacher', [TeacherController::class, 'start_up'])->name('teacher.start_up');
    Route::get('/teacher/edit-question', function () { return view('teacher.qpage'); })->name('teacher.edit_question');
    Route::post('/teacher/upload-image', [TeacherController::class, 'upload_image'])->name('teacher.upload_image');

    // exam routes (added by svmy)
    // Route::any('/teacher/exam/paper', [TeacherController::class, 'manage_question'])->name('teacher.exam.paper');
    Route::get('/teacher/exam', function () { return view('teacher.exam.index'); })->name('teacher.exam');
    Route::post('/sample', [TeacherController::class, 'store']);

    // Added by Ankan
    Route::get('/teacher/test-series/{id}', [TeacherController::class, 'testSeriesView'])->name('teacher.test_series');
    Route::get('/teacher/test-details/{test_id}', [TeacherController::class, 'testDetailsView'])->name('teacher.test_details');
});