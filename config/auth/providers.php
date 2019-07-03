<?php

return [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\User::class,
    ],

    'compredict' => [
        'driver' => 'compredict',
    ],
];
