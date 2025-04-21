<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/home", "as"    => "home."], __DIR__ . "/assets/home.php");
Route::group(["prefix" => "/about", "as"   => "about."], __DIR__ . "/assets/about.php");
Route::group(["prefix" => "/client", "as"  => "client."], __DIR__ . "/assets/client.php");
