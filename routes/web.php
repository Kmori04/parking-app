<?php

use App\Http\Controllers\CarParkingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CarParkingController::class, 'showAllCarParkingStatus'])->name('home');
Route::get('/availability', [CarParkingController::class, 'availability'])->name('availability');
Route::get('/availability/cars', [CarParkingController::class, 'cars'])->name('availability.cars');
Route::post('/availability/cars/{slotId}/status', [CarParkingController::class, 'updateStatus'])
    ->whereNumber('slotId')
    ->name('availability.cars.update');
Route::get('/availability/motors', [CarParkingController::class, 'motors'])->name('availability.motors');
Route::post('/availability/motors/{slotId}/status', [CarParkingController::class, 'updateMotorStatus'])->name('availability.motors.update');