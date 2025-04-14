<?php

use App\Modules\Healthcheck\Commands\RunHealthChecksCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(RunHealthChecksCommand::class)->everyMinute()->withoutOverlapping();
