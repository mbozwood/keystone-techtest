<?php

use Illuminate\Support\Facades\Route;

// put all routes into the vue router
Route::get('/{vue_route?}', function () {
    return view('main');
})->where('vue_route', '[\/\w\.-]*');
