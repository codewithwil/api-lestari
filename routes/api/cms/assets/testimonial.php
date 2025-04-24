<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\CMS\Testimonial\TestimonialC::class, 'index'])->name("index");
Route::get("/show/{testimonialId}", [ctr\API\CMS\Testimonial\TestimonialC::class, 'show'])->name("show");
Route::post("/store", [ctr\API\CMS\Testimonial\TestimonialC::class, 'store'])->name("store");
Route::post("/update/{testimonialId}", [ctr\API\CMS\Testimonial\TestimonialC::class, 'update'])->name("update");
Route::post("/delete/{testimonialId}", [ctr\API\CMS\Testimonial\TestimonialC::class, 'delete'])->name("delete");