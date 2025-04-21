<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\CMS\Client\ClientC::class, 'index'])->name("index");
Route::get("/show/{clientId}", [ctr\API\CMS\Client\ClientC::class, 'show'])->name("show");
Route::post("/store", [ctr\API\CMS\Client\ClientC::class, 'store'])->name("store");
Route::post("/update/{clientId}", [ctr\API\CMS\Client\ClientC::class, 'update'])->name("update");
Route::post("/delete/{clientId}", [ctr\API\CMS\Client\ClientC::class, 'delete'])->name("delete");