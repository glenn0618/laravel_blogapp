<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController; 

Route::get('/', [BlogController::class, 'index']); // Homepage
Route::resource('blogs', BlogController::class);
