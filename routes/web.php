<?php

use App\Http\Controllers\UcasApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/schedule/{student_id}', [UcasApiController::class, 'viewSchedule']);
