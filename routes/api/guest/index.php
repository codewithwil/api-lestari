<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/home", [ctr\API\CMS\Home\HomeC::class, 'index'])->name("home.index");
Route::get("/about", [ctr\API\CMS\About\AboutC::class, 'index'])->name("about.index");
Route::get("/client", [ctr\API\CMS\Client\ClientC::class, 'index'])->name("client.index");
Route::get("/service", [ctr\API\CMS\Service\ServiceC::class, 'index'])->name("service.index");