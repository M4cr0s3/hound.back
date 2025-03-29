<?php

use App\Modules\Authorization\Controller\AuthController;

Route::group(['prefix' => 'auth'], function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'jwt.auth'], function () {

        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/refresh', [AuthController::class, 'refresh']);
        Route::get('/logout', [AuthController::class, 'logout']);

    });

});
