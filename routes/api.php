<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UcasApiController;

Route::post('/ucas/login', [UcasApiController::class, 'loginAndStore']);
Route::post('/ucas/get-table', [UcasApiController::class, 'getAndStoreSchedule']);
