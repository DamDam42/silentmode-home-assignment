<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::post('/register', [ClientController::class, 'register']);
Route::get('/check-command', [ClientController::class, 'checkCommand']);
Route::post('/request-download', [ClientController::class, 'requestDownload']);
Route::post('/upload-file', [ClientController::class, 'uploadFile']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');