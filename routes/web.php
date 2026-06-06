<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ClientController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/check-command', [ClientController::class, 'checkCommand']);
// Route::post('/request-download', [ClientController::class, 'requestDownload']);
// Route::post(
//     '/register',
//     [ClientController::class, 'register']
// );