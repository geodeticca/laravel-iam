<?php

return [
    'service' => [
        'url' => env('IAM_URL'),
        'version' => env('IAM_VERSION'),
    ],

    'jwt' => [
        'iss' => env('JWT_ISS'),
        'alg' => env('JWT_ALG'),
        'secret' => env('JWT_SECRET'),
        'pubkey' => env('JWT_PUBKEY'),
    ],
];
