<?php

return [
    'service' => [
        'url' => env('IAM_URL'),
    ],

    'jwt' => [
        'iss' => env('JWT_ISS'),
        'alg' => env('JWT_ALG'),
        'secret' => storage_path(env('JWT_SECRET')),
        'pubkey' => storage_path(env('JWT_PUBKEY')),
    ],
];
