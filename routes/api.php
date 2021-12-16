<?php

use App\Http\Controllers\Events\EventController;
use Illuminate\Support\Facades\Route;



    
    Route::post("/events", [EventController::class, 'store'])->name("events.store");
    
    Route::put("/events/{event}", [EventController::class, 'update'])->name("events.update");
    Route::put("/events/{event}/partially", [EventController::class, 'updatePartially'])->name("events.update_partially");

    Route::delete("/events/{event}", [EventController::class, 'destroy'])->name("events.destroy");
    

    Route::resource("/eventsDescriptions", App\Http\Controllers\Events\EventDescriptionController::class)->except(["create","edit"]);



