<?php

use App\Modules\Healthcheck\Controller\HealthCheckController;

Route::group(['prefix' => 'healthcheck'], function () {

    Route::get('/{healthCheckEndpoint}', [HealthCheckController::class, 'show']);
    Route::patch('/{healthCheckEndpoint}', [HealthCheckController::class, 'update']);
    Route::post('/{project}', [HealthCheckController::class, 'store']);
    Route::delete('/{healthCheckEndpoint}', [HealthCheckController::class, 'destroy']);

});
