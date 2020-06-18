<?php

return [
    'service' => [
        'url' => env('IAM_URL'),
    ],

    'jwt' => [
        'iss' => env('JWT_ISS'),
        'alg' => env('JWT_ALG', 'RS256'),
        'secret' => storage_path(env('JWT_SECRET', 'jwt/secret')),
        'pubkey' => storage_path(env('JWT_PUBKEY', 'jwt/public.key')),
    ],
];
