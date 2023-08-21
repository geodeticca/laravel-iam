<?php

namespace Geodeticca\Iam;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

use GuzzleHttp\Client as GuzzleClient;
use Firebase\JWT\JWT;
use Dense\Jwt\Auth\Sign;
use Dense\Informer\Mail\InformerTrait;

use Geodeticca\Iam\Identity\StatefulIdentity;
use Geodeticca\Iam\Identity\StatelessIdentity;
use Geodeticca\Iam\Jwt\JwtProvider;
use Geodeticca\Iam\Jwt\JwtGuard;
use Geodeticca\Iam\Account\Account;
use Geodeticca\Iam\Commands\Generate;
use Geodeticca\Iam\Middleware\Authenticate;
use Geodeticca\Iam\Middleware\AutoLogin;

class IamServiceProvider extends ServiceProvider
{
    use InformerTrait;

    /**
     * @var string
     */
    protected string $namespace = 'iam';

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @return void
     */
    public function boot()
    {
        // commands
        $this->commands([
            Generate::class,
        ]);

        // langs
        $this->loadJsonTranslationsFrom(__DIR__ . '/resources/lang');

        // routes
        $this->loadRoutesFrom(__DIR__ . '/routes/iam.php');

        // config
        $this->publishes([
            __DIR__ . '/config/iam.php' => config_path('iam.php'),
        ]);

        $this->mergeConfigFrom(__DIR__ . '/config/iam.php', 'iam');

        // langs
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', $this->namespace);

        $this->publishes([
            __DIR__ . '/resources/lang' => resource_path('lang/vendor/iam'),
        ]);

        // views
        $this->loadViewsFrom(__DIR__ . '/resources/views', $this->namespace);

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/iam'),
        ]);

        // middlewares
        $this->app['router']->aliasMiddleware('iam.authenticate', Authenticate::class);
        $this->app['router']->aliasMiddleware('iam.autologin', AutoLogin::class);

        // guard used to protect web routes
        $this->app['auth']->extend('geodeticca-web', function () {
            $sign = $this->app->make(Sign::class);
            $identity = $this->app->make(StatefulIdentity::class);

            $jwtProvider = new JwtProvider($sign, $identity);

            return new JwtGuard($jwtProvider);
        });

        // guard used to protect exposed api routes that cannot use standart login procedure
        // thus do not send any JWT token in the header
        // login is done via system user account details
        $this->app['auth']->extend('geodeticca-autologin', function () {
            $sign = $this->app->make(Sign::class);
            $identity = $this->app->make(StatelessIdentity::class);

            $jwtProvider = new JwtProvider($sign, $identity);

            return new JwtGuard($jwtProvider);
        });

        // guard used to protect exposed api routes that require JWT token in the header
        $this->app['auth']->viaRequest('geodeticca-api', function () {
            $sign = $this->app->make(Sign::class);

            try {
                // decode claims data from JWT data
                $claims = $sign->decodeFromRequest();

                if ($claims) {
                    // fill user from claims
                    $account = Account::createFromJwt((array)$claims->usr);

                    return $account;
                }
            } catch (\Exception $e) {
                $this->sendException($e);
            }

            return null;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Sign::class, function () {
            $adapter = new JWT();

            return new Sign($adapter, [
                'iss' => Config::get('iam.jwt.iss'),
                'alg' => Config::get('iam.jwt.alg'),
                'pubkey' => Config::get('iam.jwt.pubkey'),
            ]);
        });

        $this->app->singleton(StatelessIdentity::class, function () {
            $baseUrl = Config::get('iam.service.url') . '/';

            $defaultOptions = [
                'base_uri' => $baseUrl,
                'verify' => false,
            ];

            // vypnute overovanie SSL certifikatov, okrem produkcneho prostredia
            //if (!$this->app->environment('prod')) {
            //    $defaultOptions = array_merge($defaultOptions, [
            //        'verify' => false,
            //    ]);
            //}

            $guzzleClient = new GuzzleClient($defaultOptions);

            $sign = $this->app->make(Sign::class);

            $identity = new StatelessIdentity($guzzleClient, $sign);
            $identity->setCredentials([
                'app' => Config::get('iam.app'),
                'login' => Config::get('iam.service.login'),
                'password' => Config::get('iam.service.password'),
            ]);

            return $identity;
        });

        $this->app->singleton(StatefulIdentity::class, function () {
            $baseUrl = Config::get('iam.service.url') . '/';

            $defaultOptions = [
                'base_uri' => $baseUrl,
                'verify' => false,
            ];

            // vypnute overovanie SSL certifikatov, okrem produkcneho prostredia
            //if (!$this->app->environment('prod')) {
            //    $defaultOptions = array_merge($defaultOptions, [
            //        'verify' => false,
            //    ]);
            //}

            $guzzleClient = new GuzzleClient($defaultOptions);

            $sign = $this->app->make(Sign::class);

            $identity = new StatefulIdentity($guzzleClient, $sign);

            return $identity;
        });
    }
}
