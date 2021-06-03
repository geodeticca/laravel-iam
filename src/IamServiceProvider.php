<?php

namespace Geodeticca\Iam;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

use GuzzleHttp\Client as GuzzleClient;
use Firebase\JWT\JWT;
use Dense\Jwt\Auth\Sign;
use Dense\Informer\Mail\InformerTrait;

use Geodeticca\Iam\Jwt\JwtProvider;
use Geodeticca\Iam\Jwt\JwtGuard;
use Geodeticca\Iam\Account\Account;
use Geodeticca\Iam\Commands\Generate;
use Geodeticca\Iam\Service\StatelessClient as IamStatelessClient;
use Geodeticca\Iam\Service\StatefulClient as IamStatefulClient;

class IamServiceProvider extends ServiceProvider
{
    use InformerTrait;

    /**
     * @var string
     */
    protected $namespace = 'iam';

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

        $this->app['auth']->extend('geodeticca-jwt', function () {
            $sign = $this->app->make(Sign::class);
            $iam = $this->app->make(IamStatefulClient::class);

            $jwtProvider = new JwtProvider($sign, $iam);

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
                return Account::createFromJwt((array)$claims->usr);
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

        $this->app->singleton(IamStatelessClient::class, function () {
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

            $connection = new GuzzleClient($defaultOptions);

            $sign = $this->app->make(Sign::class);

            $statelessClient = new IamStatelessClient($connection, $sign);
            $statelessClient->setCredentials([
                'app' => Config::get('iam.service.app'),
                'login' => Config::get('iam.service.login'),
                'password' => Config::get('iam.service.password'),
            ]);

            return $statelessClient;
        });

        $this->app->singleton(IamStatefulClient::class, function () {
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

            $connection = new GuzzleClient($defaultOptions);

            $sign = $this->app->make(Sign::class);

            $statefulClient = new IamStatefulClient($connection, $sign);

            return $statefulClient;
        });
    }
}
