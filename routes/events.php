<?php

use App\Modules\Event\Controller\EventController;
use App\Modules\Project\Middlewares\DsnSignatureMiddleware;

Route::group(['prefix' => 'events'], function () {

    Route::post('/', [EventController::class, 'store'])
        ->middleware(DsnSignatureMiddleware::class);

    Route::group(['middleware' => 'jwt.auth'], function () {

        Route::get('/', [EventController::class, 'index']);
        Route::get('/dashboard', [EventController::class, 'dashboard']);
        Route::get('/{event}', [EventController::class, 'show']);
        Route::put('/{event}', [EventController::class, 'update']);
        Route::delete('/{event}', [EventController::class, 'destroy']);

    });

});

// Route::get('/source-code', function () {
//    $filePath = request()->input('path');
//
//    if (! preg_match('/^[a-zA-Z0-9\/_.-]+$/', $filePath)) {
//        abort(400, 'Invalid file path');
//    }
//
//    $fullPath = base_path($filePath);
//
//    if (! File::exists($fullPath)) {
//        abort(404, 'File not found');
//    }
//
//    return response()->json([
//        'content' => File::get($fullPath),
//        'path' => $filePath,
//    ]);
// });
