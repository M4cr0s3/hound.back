<?php

use App\Modules\Project\Controller\ProjectController;

Route::group(['prefix' => 'projects', 'middleware' => 'jwt.auth'], function () {

    Route::get('/', [ProjectController::class, 'index']);
    Route::post('/', [ProjectController::class, 'store']);
    Route::get('/{project}', [ProjectController::class, 'show']);
    Route::put('/{project}', [ProjectController::class, 'update']);
    Route::delete('/{project}', [ProjectController::class, 'destroy']);

});
