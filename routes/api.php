<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\teacherApiController;
use App\Http\Controllers\Api\ApiController;

Route::any('/server', [ApiController::class,'server']);
Route::post('/teacher/exam/paper', [ApiController::class, 'getExamPaper'])->name('teacher.exam.paper');

Route::post('/send-verification-code', [ApiController::class, 'sendCode'])->name('send-verification-code');
Route::post('/verify-otp', [ApiController::class, 'verifyOtp'])->name('verify-otp');


// teacher api routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/test-series', [teacherApiController::class, 'getTestSeries'])->name('test-series');
    Route::post('/create-test-series', [teacherApiController::class, 'createTestSeries'])->name('create-test-series');
    Route::post('/create-test', [teacherApiController::class, 'createTest'])->name('create-test');
    Route::post('/get-tests', [teacherApiController::class, 'getTests'])->name('get-tests');
    Route::post('/save-question', [teacherApiController::class, 'saveQuestion'])->name('save-question');
    Route::post('/delete-question', [teacherApiController::class, 'deleteQuestion'])->name('delete-question');
    Route::post('/get-questions', [teacherApiController::class, 'getQuestions'])->name('get-questions');
    Route::post('/update-sequence', [teacherApiController::class, 'updateSequence'])->name('update-sequence');
});
