# COMPREDICT Users Service Provider for Laravel 5

This is a simple [Laravel](http://laravel.com/) service provider for making it easy to include the official
[COMPREDICT Users SDK for PHP](https://github.com/compredict/users-sdk-php) in your Laravel.

This README is for version 1.x of the service provider, which is implemented to work with Version 1 of the
COMPREDICT Users SDK for PHP and Laravel 5.x.

## Installation

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

To use the COMPREDICT Service Provider, you must register the provider when bootstrapping your application.


### Lumen
In Lumen find the `Register Service Providers` in your `bootstrap/app.php` and register the COMPREDICT Service Provider.

```php
    $app->register(Compredict\User\Providers\CompredictAuthServiceProvider::class);
```

### Laravel
In Laravel find the `providers` key in your `config/app.php` and register the COMPREDICT Service Provider.

```php
    'providers' => array(
        // ...
        Compredict\User\Providers\CompredictAuthServiceProvider::class,
    )
```

Find the `aliases` key in your `config/app.php` and add the COMPREDICT facade alias.

```php
    'aliases' => array(
        // ...
        'CP_User' => Compredict\User\Facades\CompredictFacade::class,
    )
```

## Configuration

By default, the package uses the following environment variables to auto-configure the plugin without modification:
```
COMPREDICT_USERS_ADMIN_KEY=  # Only needed if your application requires to register new users.
COMPREDICT_USERS_FAIL_ON_ERROR=True
```

To customize the configuration file, publish the package configuration using Artisan.

```sh
php artisan vendor:publish  --provider="Compredict\User\Providers\CompredictServiceProvider"
```

Update your settings in the generated `app/config/compredict.php` configuration file.

## Usage

In order to use the COMPREDICT's AI Core SDK for PHP within your app, you need to retrieve it from the [Laravel IoC
Container](http://laravel.com/docs/ioc). The following example gets all the algorithms allowed for the user.

```php
$user = App::make('CP_User')->login($username, $password);
```