# COMPREDICT Users Service Provider for Laravel 5

This is a simple [Laravel](http://laravel.com/) service provider for making it easy to include the official
[COMPREDICT Users SDK for PHP](https://github.com/compredict/users-sdk-php) in your Laravel.

This README is for version 1.x of the service provider, which is implemented to work with Version 1 of the
COMPREDICT Users SDK for PHP and Laravel 5.x.

## Installation via Composer

The COMPREDICT Service Provider can be installed via [Composer](http://getcomposer.org) by requiring the
`compredict/users-sdk-laravel` package in your project's `composer.json`.

```json
{
    "require": {
        "compredict/users-sdk-laravel": "dev-master"
    }
}
```

Then run a composer update
```sh
php composer.phar update
```

## COMPREDICT Configuration

By default, the package uses the following environment variables to auto-configure the plugin without modification:
```
COMPREDICT_USERS_ADMIN_KEY=  # Only needed if your application requires to register new users.
COMPREDICT_USERS_FAIL_ON_ERROR=True
```

To customize the configuration file, publish the package configuration using Artisan.

```sh
php artisan vendor:publish  --provider="Compredict\User\Providers\CompredictServiceProvider" --force
```

Update your settings in the generated `app/config/compredict.php` configuration file.

Additionally, publishing will provide you the following:

- Login and password reset views. 
- The routes associated to login and password reset.
- Add User class that inherits the Compredict User.

Registration view and route will only be added when you set the value `COMPREDICT_USERS_ADMIN_KEY`.

## User

The User class has the following attributes:

- token, APIKey. 
- id.
- username.
- first_name.
- last_name.
- email.
- organization.

Currently we support only username for login.

To update user information:

```php
$user = \Auth::guard()->user();
$user->first_name = "Ousama";
$user->last_name = "Esbel";
$user->organization = "COMPREDICT";
$user->update();
```

To refresh user's information from the API:

```php
$user = \Auth::guard()->user();
$user->fresh();
```
