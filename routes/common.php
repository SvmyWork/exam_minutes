<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;


Route::post('/register', [CommonController::class, 'register_store'])->name('common.store');
Route::post('/login', [CommonController::class, 'authenticate'])->name('common.authenticate');
Route::any('/emailverify', [CommonController::class, 'emailverify'])->name('common.emailverify');
Route::get('/reset-password', [CommonController::class, 'resetPassword'])->name('common.resetPassword');
Route::Post('/save-new-password', [CommonController::class, 'saveNewPassword'])->name('common.savePassword');