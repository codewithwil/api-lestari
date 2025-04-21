<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\CMS\Home\HomeC::class, 'index'])->name("index");
Route::get("/show/{homeId}", [ctr\API\CMS\Home\HomeC::class, 'show'])->name("show");
Route::post("/store", [ctr\API\CMS\Home\HomeC::class, 'store'])->name("store");
Route::post("/update/{homeId}", [ctr\API\CMS\Home\HomeC::class, 'update'])->name("update");
Route::post("/delete/{homeId}", [ctr\API\CMS\Home\HomeC::class, 'delete'])->name("delete");