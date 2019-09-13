<?php

namespace Compredict\User\Auth\Providers;

use App\User as CPUser;
use Compredict\API\Users\Resources\User as User;
use Compredict\User\Auth\Models\User as UserModel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;
use Illuminate\Support\Facades\Session;

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
        $user = CPUser::fetchUserByCredentials($credentials);

        if (empty($user)) {
            return;
        }

        if (is_null($user->id)) {
            return;
        }

        Session::put('user', [
            'id' => $user->id,
            'api_key' => $user->APIKey,
            'username' => $user->username,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'organization' => $user->organization,
            'phone_number' => $user->phone_number,
            'is_staff' => $user->is_staff,
        ]);

        return $user;
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
        return $user->username == $credentials["username"];
    }

    public function retrieveById($identifier)
    {
        $sessionUser = Session::get('user');

        if (!empty($sessionUser)) {
            return UserModel::processResponse(new User($sessionUser['api_key'], (object) $sessionUser));
        }

        $user = CPUser::fetchUserByToken($identifier);
        return (!empty($user) ? (is_null($user->id)) ? null : $user : null);
    }

    public function retrieveByToken($identifier, $token)
    {}

    public function updateRememberToken(Authenticatable $user, $token)
    {}
}
