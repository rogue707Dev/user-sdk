<?php

namespace Compredict\User\Auth\Models;

use Illuminate\Contracts\Auth\Authenticatable as Authenticatable;
use \App;

class User implements Authenticatable
{

    protected $rememberTokenName = false;

    protected $user;
    protected $token;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function fetchUserByCredentials(array $credentials)
    {
        $user = App::make('CP_User')::login($credentials["username"], $credentials["password"]);

        if ($user !== false) {
            $this->user = $user;
            $this->token = $user->APIKey;
        }

        return $this;
    }

    public function fetchUserByToken($identifier)
    {
        $user = App::make('CP_User')::getUser($identifier);

        if ($user !== false) {
            $this->user = $user;
            $this->token = $user->APIKey;
        }

        return $this;
    }

    public function update()
    {
        if (!isset($this->user)) {
            throw new Exception("User is not logged in!");
        }

        $this->user->update();
    }

    public static function create($data)
    {
        $user = App::make('CP_User')::registerUser($data['username'],
            $data['email'],
            $data['password1'],
            $data['password2'],
            $data['organization'],
            $data['first_name'],
            $data['last_name'],
            $data['phone_number']);

        if ($user === false) {
            return false;
        }

        $obj = new static();
        $obj->user = $user;
        $obj->token = $user->APIKey;
        return $obj;
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getAuthIdentifierName()
     */
    public function getAuthIdentifierName()
    {
        return "token";
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getAuthIdentifier()
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getAuthPassword()
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getRememberToken()
     */
    public function getRememberToken()
    {
        if (!empty($this->getRememberTokenName())) {
            return $this->{$this->getRememberTokenName()};
        }
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::setRememberToken()
     */
    public function setRememberToken($value)
    {
        if (!empty($this->getRememberTokenName())) {
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getRememberTokenName()
     */
    public function getRememberTokenName()
    {
        return $this->rememberTokenName;
    }

    public function __get($field)
    {
        // then, if a method exists for the specified field and the field we should actually be examining
        // has a value, call the method instead
        if (method_exists($this, $field) && isset($this->user->$field)) {
            return $this->$field();
        }

        if ($field == 'user') {
            return $this->user;
        }

        // otherwise, just return the field directly (or null)
        return (isset($this->user->$field)) ? $this->user->$field : null;
    }

    public function __set($field, $value)
    {
        $this->user->$field = $value;
    }
}
