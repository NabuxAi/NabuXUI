<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/livewire', \App\Livewire\Showcase::class);
Route::get('/livewire/second', \App\Livewire\Second::class);
