<?php

use App\Modules\Notification\Controller\NotificationRuleController;

Route::group(['prefix' => 'notification-rules', 'middleware' => 'jwt.auth'], function () {

    Route::delete('/{notificationRule}', [NotificationRuleController::class, 'destroy']);

});
