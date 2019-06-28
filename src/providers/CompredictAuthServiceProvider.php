<?php

namespace Compredict\User\Providers;

use Auth;
use Compredict\API\Users\Client as Client;
use Illuminate\Support\ServiceProvider;
use App\Authentication\CompredictUserProvider;

class CompredictAuthServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('compredict_users', function ($app) {
            $config = $app->make('config')->get('compredict');
            $usersConfig = $config['users'];
            $cp_user_client = Client::getInstance($usersConfig['admin_key']);
            $cp_user_client->failOnError($usersConfig['fail_on_error']);
            return $cp_user_client;
        });

        $this->app->alias('compredict_users', 'Compredict\API\Users\Client');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $source = dirname(__DIR__).'/config/compredict.php';
        $this->publishes([$source => config_path('compredict.php')]);
        $this->mergeConfigFrom($source, 'compredict');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['compredict_users', 'Compredict\API\Users\Client'];
    }
}
