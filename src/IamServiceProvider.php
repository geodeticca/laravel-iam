<?php

namespace Geodeticca\Iam;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

use GuzzleHttp\Client as GuzzleClient;
use Firebase\JWT\JWT;
use Dense\Jwt\Auth\Sign;

use Geodeticca\Iam\Service\Client as IamClient;

use Geodeticca\Iam\Commands\Generate;

class IamServiceProvider extends ServiceProvider
{
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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Sign::class, function () {
            $adapter = new JWT();

            return new Sign($adapter, [
                'iss' => Config::get('jwt.iss'),
                'alg' => Config::get('jwt.alg'),
                'pubkey' => Config::get('jwt.pubkey'),
            ]);
        });

        $this->app->bind(IamClient::class, function () {
            $baseUrl = implode('/', [
                Config::get('iam.url'),
                Config::get('iam.version'),
            ]);

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

            return new IamClient($connection);
        });
    }
}
