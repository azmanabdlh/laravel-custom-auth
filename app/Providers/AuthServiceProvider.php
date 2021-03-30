<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Services\Auth\JWTGuard;
use App\Services\Auth\FirebaseJWTAuthService;
use App\Services\Contracts\Auth\JWTAuthServiceInterface;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerJwtAuthentication();

    }

    private function registerJwtAuthentication()
    {

        $this->app->bind(JWTAuthServiceInterface::class, function() {
            return new FirebaseJWTAuthService(
                config('auth.guards.jwt')
            );
        });

        Auth::extend('jwt', function ($app, $name, array $config) {
            $jwtAuthServiceInstance = $app->get(JWTAuthServiceInterface::class);

            return new JWTGuard(
                $jwtAuthServiceInstance,
                $config
            );
        });
    }
}
