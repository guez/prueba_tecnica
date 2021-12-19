<?php

use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Categories\CategoryDescriptionsController;
use App\Http\Controllers\Events\EventAssistantsController;
use App\Http\Controllers\Events\EventController;
use App\Http\Controllers\Events\EventDescriptionsController;
use Illuminate\Support\Facades\Route;




Route::prefix("event")->group(function () {

    Route::post("/events", [EventController::class, 'store'])->name("events.store");

    Route::put("/events/{event}", [EventController::class, 'update'])->name("events.update");
    Route::put("/events/{event}/partially", [EventController::class, 'updatePartially'])->name("events.update_partially");
    Route::delete("/events/{event}", [EventController::class, 'destroy'])->name("events.destroy");

    Route::resource("/event_descriptions", EventDescriptionsController::class)->except(["create", "edit"]);

    Route::post("/buyTickets", [EventAssistantsController::class, 'store'])->name("events_assistants.store");
});


Route::prefix("category")->group(function () {

    Route::post("/categories", [CategoryController::class, 'store'])->name("categories.store");
    Route::put("/categories/{category}", [CategoryController::class, 'update'])->name("categories.update");
    Route::delete("/categories/{category}", [CategoryController::class, 'destroy'])->name("categories.destroy");

    Route::resource("/category_descriptions", CategoryDescriptionsController::class)->except(["create", "edit"]);

});
