<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


require __DIR__.'/student.php';
require __DIR__.'/teacher.php';
require __DIR__.'/exam.php';
require __DIR__.'/common.php';