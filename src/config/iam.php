<?php

return [
    'app' => env('IAM_APP'),

    'service' => [
        'url' => env('IAM_URL'),
        'login' => env('IAM_SERVICE_LOGIN'),
        'password' => env('IAM_SERVICE_PASSWORD'),
    ],

    'jwt' => [
        'iss' => env('JWT_ISS'),
        'alg' => env('JWT_ALG', 'RS256'),
        'secret' => storage_path(env('JWT_SECRET', 'jwt/secret')),
        'pubkey' => storage_path(env('JWT_PUBKEY', 'jwt/public.key')),
    ],
];
