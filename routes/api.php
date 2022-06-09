<?php

use App\Http\Controllers\Api\ActionTypeController;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\ClientShowsController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\ShowController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['as' => 'api.', 'middleware' => 'auth:sanctum'], function () {
    // ACTION TYPES
    Route::get('/action-types', [ActionTypeController::class, 'index'])->name('action-types.index');

    // EVENT TYPES
    Route::get('/event-types', [EventTypeController::class, 'index'])->name('event-types.index');

    Route::post('/events', [EventController::class, 'store'])->name('events.store');

    // SHOWS ROUTES
    Route::get('shows', [ShowController::class, 'index'])->name('shows.index');

    Route::get('shows/{show}', [ShowController::class, 'show'])->name('shows.show');

    // CLIENT SHOWS ROUTES
    Route::get('clients/{client}/shows', [ClientShowsController::class, 'index'])->name('client.shows.index');

    Route::get('clients/{client}/shows/{show}', [ClientShowsController::class, 'show'])->name('client.shows.show');

    // ATTENDEE ROUTES
    Route::post('attendees', [AttendeeController::class, 'store'])->name('attendees.store');
    Route::get('attendees/{attendee}', [AttendeeController::class, 'show'])->name('attendees.show');
});
