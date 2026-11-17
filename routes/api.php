<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

/*
|--------------------------------------------------------------------------
| API Routes - Versión 1 (v1)
|--------------------------------------------------------------------------
| Rutas RESTful para la gestión del recurso 'productos'
| URL Base: /api/v1/productos
*/

Route::prefix('v1')->group(function () {
    Route::get('/productos', [ProductoController::class, 'index']);
    Route::get('/productos/{id}', [ProductoController::class, 'show']);
    Route::post('/productos', [ProductoController::class, 'store']);
    Route::put('/productos/{id}', [ProductoController::class, 'update']);
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);
});
