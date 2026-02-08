<?php

namespace App\Providers;

use App\Repositories\Contracts\RefreshTokenRepositoryInterface;
use App\Repositories\RefreshTokenRepository;
use App\Services\Auth\Contracts\AuthServiceInterface;
use App\Services\Auth\Contracts\CurrentUserProviderInterface;
use App\Services\Auth\JwtAuthService;
use App\Services\Auth\JwtCurrentUserProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(
            RefreshTokenRepositoryInterface::class,
            RefreshTokenRepository::class
        );

        $this->app->scoped(
            AuthServiceInterface::class,
            JwtAuthService::class
        );

        $this->app->scoped(
            CurrentUserProviderInterface::class,
            JwtCurrentUserProvider::class
        );
    }

    public function boot(): void
    {
    }
}
