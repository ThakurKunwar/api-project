<?php

use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\GithubController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/home', [PostController::class, 'haha']);


Route::get('/external-posts', [PostController::class, 'api']);
Route::get('/github/{username}', [GithubController::class, 'profile']);
