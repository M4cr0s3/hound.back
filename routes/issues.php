<?php

use App\Modules\Issue\Controller\IssueController;

Route::group(['prefix' => 'issues', 'middleware' => 'jwt.auth'], function () {

    Route::get('/', [IssueController::class, 'index']);
    Route::get('/{issue}', [IssueController::class, 'show']);
    Route::patch('/{issue}', [IssueController::class, 'update']);
    Route::post('/', [IssueController::class, 'store']);
    Route::delete('/{issue}', [IssueController::class, 'destroy']);
    Route::post('/{issue}/comments', [IssueController::class, 'comment']);
    Route::post('/{issue}/assignees', [IssueController::class, 'assign']);
    Route::delete('/{issue}/assignees/{assigneeId}', [IssueController::class, 'removeAssign']);

});
