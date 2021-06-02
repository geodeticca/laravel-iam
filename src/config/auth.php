<?php

return [
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
        ],
    ],
];
