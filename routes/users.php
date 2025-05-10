<?php

use App\Modules\User\Controller\UserController;

Route::group(['prefix' => 'users', 'middleware' => 'jwt.auth'], function () {

    Route::get('/', [UserController::class, 'index']);
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/search', [UserController::class, 'search']);
    Route::post('/available', [UserController::class, 'availableToAssign']);
    Route::post('/profile/password', [UserController::class, 'changePassword']);
    Route::post('/', [UserController::class, 'store']);
    Route::delete('/{user}', [UserController::class, '']);

});
