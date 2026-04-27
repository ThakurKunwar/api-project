<?php

use App\Http\Controllers\Api\V1\PostController as V1PostController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hello', function () {
    return [
        "message" => "hello Laravel API"
    ];
});


Route::prefix('v1')->group(function () {
    Route::apiResource('posts', V1PostController::class);
});
