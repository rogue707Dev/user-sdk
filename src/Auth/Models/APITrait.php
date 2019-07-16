<?php

namespace Compredict\User\Auth\Models;

use \App;

trait APITrait
{

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public static function fetchUserByCredentials(array $credentials)
    {
        $response = App::make('CP_User')::login($credentials["username"], $credentials["password"]);

        return self::processResponse($response);
    }

    public static function fetchUserByToken($identifier)
    {
        $response = App::make('CP_User')::getUser($identifier);

        return self::processResponse($response);
    }

    public static function create($data)
    {
        $response = App::make('CP_User')::registerUser($data['username'],
            $data['email'],
            $data['password1'],
            $data['password2'],
            $data['organization'],
            $data['first_name'],
            $data['last_name'],
            $data['phone_number'],
            true);

        return is_object($response) ? self::processResponse($response) : $response;
    }

    private static function processResponse($response)
    {
        if ($response === false) {
            return false;
        }

        $obj = new static();
        $obj->user = $response;
        $obj->token = $response->APIKey;
        return $obj;
    }

}
