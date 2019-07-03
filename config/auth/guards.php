<?php

return [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'token',
        'provider' => 'users',
        'hash' => false,
    ],

    'compredict' => [
        'driver' => 'session',
        'provider' => 'compredict',
    ],
];
