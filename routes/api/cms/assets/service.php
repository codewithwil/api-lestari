<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\CMS\Service\ServiceC::class, 'index'])->name("index");
Route::get("/show/{serviceId}", [ctr\API\CMS\Service\ServiceC::class, 'show'])->name("show");
Route::post("/store", [ctr\API\CMS\Service\ServiceC::class, 'store'])->name("store");
Route::post("/update/{serviceId}", [ctr\API\CMS\Service\ServiceC::class, 'update'])->name("update");
Route::post("/delete/{serviceId}", [ctr\API\CMS\Service\ServiceC::class, 'delete'])->name("delete");