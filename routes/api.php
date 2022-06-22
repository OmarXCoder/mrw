<?php

use App\Http\Controllers\Api\ActionTypeController;
use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ClientShowsController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\ShowController;
use App\Http\Controllers\Api\ShowEventsController;
use App\Http\Controllers\ShowAppsController;
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

    // CLIENTS ROUTES
    Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show');

    // SHOWS ROUTES
    Route::get('shows', [ShowController::class, 'index'])->name('shows.index');
    Route::get('shows/{show}', [ShowController::class, 'show'])->name('shows.show');

    // CLIENT SHOWS ROUTES
    Route::get('clients/{client}/shows', [ClientShowsController::class, 'index'])->name('clients.shows.index');
    Route::post('clients/{client}/shows', [ClientShowsController::class, 'store'])->name('clients.shows.store');
    Route::get('clients/{client}/shows/{show}', [ClientShowsController::class, 'show'])->name('clients.shows.show');

    // APPS ROUTES
    Route::get('apps', [AppController::class, 'index'])->name('apps.index');
    Route::get('apps/{app}', [AppController::class, 'show'])->name('apps.show');

    // SHOW APPS ROUTES
    Route::get('shows/{show}/apps', [ShowAppsController::class, 'index'])->name('shows.apps.index');
    Route::post('shows/{show}/apps', [ShowAppsController::class, 'store'])->name('shows.apps.store');

    // ATTENDEE ROUTES
    Route::post('attendees', [AttendeeController::class, 'store'])->name('attendees.store');
    Route::get('attendees/{attendee}', [AttendeeController::class, 'show'])->name('attendees.show');

    // EVENTS ROUTES
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');

    // SHOW EVENTS ROUTES
    Route::get('/shows/{show}/events', [ShowEventsController::class, 'index'])->name('shows.events.index');
});
