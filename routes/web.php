<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ListFiles;

Route::get('/', function () {
    return view('home');
});

Route::post('/', ImageUploadController::class);