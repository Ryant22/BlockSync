<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/stats/categories', [\App\Http\Controllers\StatController::class, 'categories'])
->name('stats.categories');
Route::get('/stats/categories/{category}/keys', [\App\Http\Controllers\StatController::class, 'keysByCategory'])
->name('stats.keys.byCategory');
Route::get('/stats/players/{uuidOrUsername}', [\App\Http\Controllers\StatController::class, 'statsByPlayer'])
->name('stats.byPlayer');
// statsByPlayerAndCategory
Route::get('/stats/players/{uuidOrUsername}/{category}', [\App\Http\Controllers\StatController::class, 'statsByPlayerAndCategory'])
->name('stats.byPlayerAndCategory');
Route::get('/stats/players/{uuidOrUsername}/{category}/{key}', [\App\Http\Controllers\StatController::class, 'statsByPlayerAndCategoryAndKey'])
->name('stats.byPlayerCategoryAndKey');

