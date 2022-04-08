<?php

namespace App\Providers;

use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $token = $request->header('token');
            if ($token) {
                try {
                    //decode jwt token
                    $validate_token = JWT::decode($token, new Key('jwt_key1', 'HS256'));
                    
                    return User::select('id', 'username', 'status')
                        ->where('token', $token)->first();
                } catch (\Throwable $th) {
                    abort(403, $th->getMessage());
                }
            }
        });
    }
}
