<?php

use App\Modules\Team\Controller\TeamController;

Route::group(['prefix' => 'teams', 'middleware' => 'jwt.auth'], function () {

    Route::get('/', [TeamController::class, 'index']);
    Route::post('/', [TeamController::class, 'store']);
    Route::get('/{team}', [TeamController::class, 'show']);
    Route::put('/{team}', [TeamController::class, 'update']);
    Route::delete('/{team}', [TeamController::class, 'destroy']);
    Route::post('/{team}/members', [TeamController::class, 'addMember']);

});
