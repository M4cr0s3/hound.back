<?php

namespace App\Providers;

use App\Models\Project;
use App\Modules\Authorization\Guard\RefreshTokenGuard;
use App\Modules\Integration\Github\GithubApiClient;
use App\Modules\Integration\Github\GithubRepositoryProvider;
use App\Modules\Project\Policy\ProjectPolicy;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GithubApiClient::class, function (Application $app) {
            return new GithubApiClient(
                $app->make(HttpClient::class),
                config('services.github.app_id'),
                config('services.github.private_key')
            );
        });

        $this->app->singleton(GithubRepositoryProvider::class, function (Application $app) {
            return new GithubRepositoryProvider(
                $app->make(GithubApiClient::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        \Gate::policy(Project::class, ProjectPolicy::class);

        \Auth::extend('refresh', function ($app, $name, array $config) {
            return new RefreshTokenGuard(
                \Auth::createUserProvider($config['provider']),
                $app->make('request')
            );
        });
    }
}
