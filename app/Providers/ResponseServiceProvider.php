<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('jsonError', function($code, $values) {
            return Response::json(
                array_merge($values, [
                    'status' => 'Error',
                    'statusCode' => $code
                ]),
                $code
            );
        });

        Response::macro('jsonSuccess', function($code, $values) {
            return Response::json(
                array_merge($values, [
                    'status' => 'Successfully',
                    'statusCode' => $code
                ]),
                $code
            );
        });
    }
}
