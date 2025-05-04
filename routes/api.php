<?php

use App\Modules\Search\Controller\SearchController;

require_once __DIR__.'/auth.php';

require_once __DIR__.'/users.php';

require_once __DIR__.'/projects.php';

require_once __DIR__.'/teams.php';

require_once __DIR__.'/events.php';

require_once __DIR__.'/issues.php';

require_once __DIR__.'/healthcheck.php';

require_once __DIR__.'/notificationRules.php';

require_once __DIR__.'/invites.php';

Route::get('/search', [SearchController::class, 'globalSearch']);
