<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceOrderController;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');


});

// Exemplo de rota pública
Route::get('/ping', function () {
    return response()->json(['pong' => true]);

});
Route::get('/service-orders', [ServiceOrderController::class, 'index']);
Route::post('/service-orders', [ServiceOrderController::class, 'store'])->name('service-orders.store');


Route::get('/token', function (Request $request) {
    $token = $request->session()->token();

    $token = csrf_token();

    return response()->json(['token' => $token]);
});

