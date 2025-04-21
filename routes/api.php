<?php

use App\Http\Controllers\API\Auth\AuthC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthC::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthC::class, 'logout']);
    Route::group(["prefix" => "/cms", "as" => "cms."], __DIR__ . "/api/cms/index.php");
});