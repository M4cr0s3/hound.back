<?php

use App\Modules\Invite\Controller\InviteController;

Route::group(['prefix' => 'invites'], function () {

    Route::post('/', [InviteController::class, 'store'])->middleware('jwt.auth');
    Route::get('/{invite}', [InviteController::class, 'show']);
    Route::post('/{invite}/activate', [InviteController::class, 'activate']);

});
