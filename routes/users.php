<?php

use App\Modules\User\Controller\UserController;

Route::group(['prefix' => 'users', 'middleware' => 'jwt.auth'], function () {

    Route::get('/', [UserController::class, 'index']);
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/search', [UserController::class, 'search']);
    Route::get('/available', [UserController::class, 'availableToAssign']);
    Route::post('/', [UserController::class, 'store']);

});
