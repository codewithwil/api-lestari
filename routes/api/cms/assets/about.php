<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\CMS\About\AboutC::class, 'index'])->name("index");
Route::get("/show/{aboutId}", [ctr\API\CMS\About\AboutC::class, 'show'])->name("show");
Route::post("/store", [ctr\API\CMS\About\AboutC::class, 'store'])->name("store");
Route::post("/update/{aboutId}", [ctr\API\CMS\About\AboutC::class, 'update'])->name("update");
Route::post("/delete/{aboutId}", [ctr\API\CMS\About\AboutC::class, 'delete'])->name("delete");