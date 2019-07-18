<?php

namespace Compredict\User\Auth\Providers;

use App\User as CPUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;

class UserProvider implements IlluminateUserProvider
{

    /**
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        $user = CPUser::fetchUserByCredentials($credentials);

        return ( empty($user) ? null : (is_null($user->username) ? null : $user));
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials  Request credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $user = CPUser::fetchUserByToken($user->APIKey);
        return $user->username == $credentials["username"];
    }

    public function retrieveById($identifier)
    {
        $user = CPUser::fetchUserByToken($identifier);
        return (is_null($user->username)) ? null : $user;
    }

    public function retrieveByToken($identifier, $token)
    {}

    public function updateRememberToken(Authenticatable $user, $token)
    {}
}
