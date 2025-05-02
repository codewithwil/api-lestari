<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/home", [ctr\API\CMS\Home\HomeC::class, 'index'])->name("home.index");
Route::get("/about", [ctr\API\CMS\About\AboutC::class, 'index'])->name("about.index");