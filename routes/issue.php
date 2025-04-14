<?php

use App\Modules\Issue\Controller\IssueController;

Route::group(['prefix' => 'issues', 'middleware' => 'jwt.auth'], function () {

    Route::post('/', [IssueController::class, 'store']);

});
