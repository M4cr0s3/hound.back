<?php

use App\Modules\Comment\Controller\CommentController;

Route::group(['prefix' => 'comments', 'middleware' => 'jwt.auth'], function () {

    Route::delete('/{comment}', [CommentController::class, 'destroy']);

});
