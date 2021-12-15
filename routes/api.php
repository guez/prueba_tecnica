<?php

use App\Http\Controllers\Events\EventController;
use Illuminate\Support\Facades\Route;



    
    Route::post("/events", [EventController::class, 'create'])->name("events.store");
    Route::put("/events/{event}", [EventController::class, 'index'])->name("events.update");
    Route::delete("/events/{event}", [EventController::class, 'create'])->name("events.destroy");

    Route::resource("/eventsDescriptions", App\Http\Controllers\Events\EventDescriptionController::class)->except(["create","edit"]);



