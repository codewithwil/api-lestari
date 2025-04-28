<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/home", [ctr\API\CMS\Home\HomeC::class, 'index'])->name("index");