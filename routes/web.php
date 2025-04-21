<?php

use App\Http\Controllers\API\Auth\AuthC;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

