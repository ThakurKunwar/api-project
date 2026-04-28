<?php

use App\Http\Controllers\Api\V1\PostController as V1PostController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('v1')->group(function () {
        Route::apiResource('posts', V1PostController::class);
    });
});



require __DIR__ . '/auth.php';
