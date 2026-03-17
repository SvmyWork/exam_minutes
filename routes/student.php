<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Middleware\StudentsMiddleware;


// Define a route for the teacher
// Route::get('/teacher', [TeacherController::class, 'start_up'])->name('teacher.start_up');
// Route::get('/teacher/edit-question', function () { return view('teacher.qpage'); })->name('teacher.edit_question');
// Route::post('/teacher/upload-image', [TeacherController::class, 'upload_image'])->name('teacher.upload_image');
// Route::get('/teacher/exam', function () { return view('teacher.exam.index'); })->name('teacher.exam');
// Route::get('/teacher/exam/cal', function () { return view('teacher.exam.scientific_calculator'); })->name('teacher.exam.cal');

// Route::get('/teacher/exam/paper', [TeacherController::class, 'manage_question'])->name('teacher.exam.paper');

// Added by Ankan

Route::get('/student-login', [StudentController::class, 'student_login'])->name('student.login');
Route::get('/student-register', [StudentController::class, 'student_register'])->name('student.register');
Route::get('/student-forgot', [StudentController::class, 'student_forgot'])->name('student.forgot');

// Added by SVMY

// Protected student routes
Route::middleware(['web', StudentsMiddleware::class])->group(function () {
    // Route::get('/student', [StudentController::class, 'student_login'])->name('common.student_login');
    Route::get('/student/home', [StudentController::class, 'student_home'])->name('student.home');
    Route::get('/student/exam/start', [StudentController::class, 'student_exam'])->name('student.exam');
});