<?php

use App\Http\Controllers\CarParkingController;
use App\Http\Controllers\ParkingRegistryController;
use App\Http\Controllers\ParkingRecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CarParkingController::class, 'showAllCarParkingStatus'])->name('home');
Route::get('/availability', [CarParkingController::class, 'availability'])->name('availability');
Route::get('/availability/cars', [CarParkingController::class, 'cars'])->name('availability.cars');
Route::post('/availability/cars/{slotId}/status', [CarParkingController::class, 'updateStatus'])
    ->whereNumber('slotId')
    ->name('availability.cars.update');
Route::get('/availability/motors', [CarParkingController::class, 'motors'])->name('availability.motors');
Route::post('/availability/motors/{slotId}/status', [CarParkingController::class, 'updateMotorStatus'])->name('availability.motors.update');

Route::get('/availability/vips', [CarParkingController::class, 'vips'])
    ->name('availability.vips');

Route::get('/vips', [CarParkingController::class, 'vips'])
    ->name('vips');

Route::patch('/vips/{slotId}/status', [CarParkingController::class, 'updateVipStatus'])
    ->whereNumber('slotId')
    ->name('vips.update');

Route::get('/registry', [ParkingRegistryController::class, 'index'])->name('registry.index');
Route::get('/user-data', [ParkingRegistryController::class, 'userData'])->name('userData');

Route::get('/users', [ParkingRegistryController::class, 'userData'])->name('users.index');

Route::get('/users/{entry}/edit', [ParkingRegistryController::class, 'edit'])
    ->whereNumber('entry')
    ->name('users.edit');

Route::put('/users/{entry}', [ParkingRegistryController::class, 'update'])
    ->whereNumber('entry')
    ->name('users.update');


// ----- [ADD] begin: create & delete routes -----

// Create (Add) new parker record
Route::post('/user-data', [App\Http\Controllers\ParkingRegistryController::class, 'store'])
    ->name('users.store');

// Delete parker record
Route::delete('/users/{entry}', [App\Http\Controllers\ParkingRegistryController::class, 'destroy'])
    ->whereNumber('entry')
    ->name('users.destroy');

// ----- [ADD] end -----

Route::get('/parking-records', [ParkingRecordController::class, 'index'])
    ->name('records.index');

// ----- [ADD] begin: parking records CRUD routes -----

// Add a new parking record
Route::post('/parking-records', [ParkingRecordController::class, 'store'])
    ->name('records.store');

// Optional edit page (kept for future use)
Route::get('/parking-records/{record}/edit', [ParkingRecordController::class, 'edit'])
    ->whereNumber('record')
    ->name('records.edit');

// Update an existing record
Route::put('/parking-records/{record}', [ParkingRecordController::class, 'update'])
    ->whereNumber('record')
    ->name('records.update');

// Delete a record
Route::delete('/parking-records/{record}', [ParkingRecordController::class, 'destroy'])
    ->whereNumber('record')
    ->name('records.destroy');

// ----- [ADD] end -----
