# User authentication via JWT for Lavarel by Geodeticca

Simple package extending laravel auth via JWT.

## About

Package that it is required is dense\enum package which is just few simple classes for easier enumerations handling. 

## Instalation

Run following composer command.
```
composer require geodeticca/iam
composer require laravel/ui
```

Run artisan command
```
php artisan iam:generate
```
This command will generate controllers in App\Http\Conrollers\Iam directory.

## Configuration

Add these lines on top of the web routes configration file in routes/web.php. 
```
Auth::routes([
    'register' => false,
    'verify' => false,
    'reset' => false,
]);
```

Add these settings to config/auth.php file.
```
'defaults' => [
    'guard' => 'web',
    'passwords' => 'users',
],

'guards' => [
    'web' => [
        'driver' => 'geodeticca-stateful',
    ],

    'api' => [
        'driver' => 'geodeticca-stateless',
        'driver' => 'geodeticca-api',
    ],
],
```

If you are running lumen you need to add following lines to bootstrap/app.php file.
```
$app->register(\Geodeticca\Iam\IamServiceProvider::class);
```
