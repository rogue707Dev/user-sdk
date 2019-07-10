<?php

namespace Compredict\User\Auth\Models;

use Illuminate\Contracts\Auth\Authenticatable as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    use APITrait;

    protected $rememberTokenName = false;
    public $timestamps = false;

    protected $user;
    protected $token;

    public function update(array $attributes = [], array $options = [])
    {
        if (!isset($this->user)) {
            throw new Exception("User is not logged in!");
        }

        $this->user->update();
    }

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        // If the "saving" event returns false we'll bail out of the save and return
        // false, indicating that the save failed. This provides a chance for any
        // listeners to cancel save operations if validations fail or whatever.
        if ($this->fireModelEvent('saving') === false) {
            return false;
        }

        // If the model already exists in the database we can just update our record
        // that is already in this database using the current IDs in this "where"
        // clause to only update this model. Otherwise, we'll just insert them.
        if (isset($this->user)) {
            $saved = $this->user->update();
        }

        // If the model is successfully saved, we need to do a few more things once
        // that is done. We will call the "saved" method here to run any actions
        // we need to happen after a model gets successfully saved right here.
        if ($saved !== false) {
            $this->finishSave($options);
        }

        return ($saved === false) ? false : true;
    }

    public function fresh($with = [])
    {
        if (!isset($this->user)) {
            throw new \Exception("User not logged in!");
        }

        $response = $this->user->fresh();
        return self::processResponse($response);
    }

    public function refresh()
    {
        $user = $this->fresh();
        $this->user = $user;
        $this->token = $user->APIKey;

        return $this;
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
