<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Compredict SDK Configuration
    |--------------------------------------------------------------------------
    |
    | The configuration options set in this file will be passed directly to the
    | `Compredict\Sdk` object, from which all client objects are created. The
    | minimum required options are declared here, but the full set of possible
    | options are documented.
    |
     */
    'ai_core' => [
        'key' => env('COMPREDICT_AI_CORE_KEY', ''),
        'user' => env('COMPREDICT_AI_CORE_USER', ''),
        'callback' => env('COMPREDICT_AI_CORE_CALLBACK', null),
        'fail_on_error' => env('COMPREDICT_AI_CORE_FAIL_ON_ERROR', true),
        'ppk' => env('COMPREDICT_AI_CORE_PPK', null),
        'passphrase' => env('COMPREDICT_AI_CORE_PASSPHRASE', ""),
    ],

    'users' => [
        'admin_key' => env('COMPREDICT_USERS_ADMIN_KEY', null),
        'fail_on_error' => env('COMPREDICT_USERS_FAIL_ON_ERROR', false),
    ],
];
