<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\CMS\CountUp\CountUpC::class, 'index'])->name("index");
Route::get("/show/{countUpId}", [ctr\API\CMS\CountUp\CountUpC::class, 'show'])->name("show");
Route::post("/store", [ctr\API\CMS\CountUp\CountUpC::class, 'store'])->name("store");
Route::post("/update/{countUpId}", [ctr\API\CMS\CountUp\CountUpC::class, 'update'])->name("update");
Route::post("/delete/{countUpId}", [ctr\API\CMS\CountUp\CountUpC::class, 'delete'])->name("delete");