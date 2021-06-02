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
use Geodeticca\Iam\Middleware\AutoLogin;

class IamServiceProvider extends ServiceProvider
{
    use InformerTrait;

    /**
     * @var string
     */
    protected string $namespace = 'iam';

    /**
     * Bootstrap any application services.
     *
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
            __DIR__ . '/config/auth.php' => config_path('auth.php'),
        ]);

        $this->mergeConfigFrom(__DIR__ . '/config/iam.php', 'iam');

        $this->mergeConfigFrom(__DIR__ . '/config/auth.php', 'auth');

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
        $this->app['router']->aliasMiddleware('iam.autologin', AutoLogin::class);

        $this->app['auth']->extend('geodeticca-stateful', function () {
            $sign = $this->app->make(Sign::class);
            $identity = $this->app->make(StatefulIdentity::class);

            $jwtProvider = new JwtProvider($sign, $identity);

            return new JwtGuard($jwtProvider);
        });

        $this->app['auth']->extend('geodeticca-stateless', function () {
            $sign = $this->app->make(Sign::class);
            $identity = $this->app->make(StatelessIdentity::class);

            $jwtProvider = new JwtProvider($sign, $identity);

            return new JwtGuard($jwtProvider);
        });

        $this->app['auth']->viaRequest('geodeticca-api', function () {
            $sign = $this->app->make(Sign::class);

            $claims = null;
            
            try {
                $claims = $sign->decode();
            } catch (\Exception $e) {
                $this->sendException($e);
            }

            if ($claims) {
                $account = Account::createFromJwt((array)$claims->usr);

                return $account;
            }
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
                'verify' => true,
            ];

            // vypnute overovanie SSL certifikatov, okrem produkcneho prostredia
            if (!$this->app->environment('prod')) {
                $defaultOptions = array_merge($defaultOptions, [
                    'verify' => false,
                ]);
            }

            $guzzleClient = new GuzzleClient($defaultOptions);

            $sign = $this->app->make(Sign::class);

            $identity = new StatelessIdentity($guzzleClient, $sign);
            $identity->setCredentials([
                'app' => Config::get('iam.service.app'),
                'login' => Config::get('iam.service.login'),
                'password' => Config::get('iam.service.password'),
            ]);

            return $identity;
        });

        $this->app->singleton(StatefulIdentity::class, function () {
            $baseUrl = Config::get('iam.service.url') . '/';

            $defaultOptions = [
                'base_uri' => $baseUrl,
                'verify' => true,
            ];

            // vypnute overovanie SSL certifikatov, okrem produkcneho prostredia
            if (!$this->app->environment('prod')) {
                $defaultOptions = array_merge($defaultOptions, [
                    'verify' => false,
                ]);
            }

            $guzzleClient = new GuzzleClient($defaultOptions);

            $sign = $this->app->make(Sign::class);

            $identity = new StatefulIdentity($guzzleClient, $sign);

            return $identity;
        });
    }
}
