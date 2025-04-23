<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/home", "as"    => "home."], __DIR__ . "/assets/home.php");
Route::group(["prefix" => "/about", "as"   => "about."], __DIR__ . "/assets/about.php");
Route::group(["prefix" => "/client", "as"  => "client."], __DIR__ . "/assets/client.php");
Route::group(["prefix" => "/service", "as"  => "service."], __DIR__ . "/assets/service.php");
Route::group(["prefix" => "/countUp", "as"  => "countUp."], __DIR__ . "/assets/countUp.php");
