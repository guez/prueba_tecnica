<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function ()
{
    return redirect()->route('events.index');
});



Route::resource("/events", App\Http\Controllers\Events\EventController::class)->except(["store", "update", "delete"]);
Route::resource("/categories", App\Http\Controllers\Categories\CategoryController::class)->except(["store", "update", "delete"]);