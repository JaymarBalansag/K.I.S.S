<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // Ensure 'app' matches your Vue entry blade file
})->where('any', '.*');
