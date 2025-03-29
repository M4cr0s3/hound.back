<?php

namespace App\Providers;

use App\Models\Project;
use App\Modules\Project\Policy\ProjectPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        \Gate::policy(Project::class, ProjectPolicy::class);
    }
}
