<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceOrderController;

// Exemplo de rota pública
Route::get('/ping', function () {
    return response()->json(['pong' => true]);

});
Route::post('/service-orders', [ServiceOrderController::class, 'store']);
Route::get('/service-orders', [ServiceOrderController::class, 'index']);

