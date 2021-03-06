<?php

namespace Compredict\User\Providers;

use App\Authentication\CompredictUserProvider;
use Auth;
use Compredict\API\Users\Client as Client;
use Compredict\User\Auth\Models\User;
use Compredict\User\Auth\Providers\UserProvider;
use Illuminate\Support\ServiceProvider;

class CompredictAuthServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Merge auth configuration
        $this->publishAuthConfig();

        //Merge Compredict configuration
        $source = dirname(__DIR__) . '/../config/compredict.php';
        $this->mergeConfigFrom($source, 'compredict');

        $this->app->singleton('compredict_users', function ($app) {
            $config = $app->make('config')->get('compredict');
            $usersConfig = $config['users'];
            $cp_user_client = Client::getInstance($usersConfig['admin_key']);
            $cp_user_client->failOnError($usersConfig['fail_on_error']);
            $cp_user_client->setURL(env('COMPREDICT_SERVER_URL', $cp_user_client->getURL()));
            return $cp_user_client;
        });

        $this->app->alias('compredict_users', 'Compredict\API\Users\Client');

        Auth::provider('compredict', function ($app, array $config) {
            return new CompredictUserProvider();
        });

        // register User class and User Provider
        $this->app->bind('App\User', function ($app) {
            return new User();
        });

        // add custom guard provider
        Auth::provider('compredict', function ($app, array $config) {
            return new UserProvider();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();
        $this->publishRoutes();
        $this->publishViews();
        $this->publishUser();
    }

    protected function publishConfig()
    {
        $source = dirname(__DIR__) . '/../config/compredict.php';
        $this->publishes([$source => config_path('compredict.php')]);
        $this->mergeConfigFrom($source, 'compredict');
    }

    protected function publishAuthConfig()
    {
        $source = dirname(__DIR__) . '/../config/auth/guards.php';
        $this->mergeConfigFrom($source, 'auth.guards');

        $source = dirname(__DIR__) . '/../config/auth/providers.php';
        $this->mergeConfigFrom($source, 'auth.providers');

        $source = dirname(__DIR__) . '/../config/auth/defaults.php';
        $this->mergeConfigFrom($source, 'auth');
        $this->app['config']->set('auth.defaults.guard', 'compredict');
    }

    protected function publishRoutes()
    {
        $source = dirname(__DIR__) . '/Auth/Routes/web.php';
        $this->loadRoutesFrom($source);
    }

    protected function publishViews()
    {
        $source = dirname(__DIR__) . '/Auth/Views/auth';
        $this->loadViewsFrom($source, 'compredict');
        $this->publishes([$source => resource_path('/views/auth')], 'views');
    }

    protected function publishUser()
    {
        $source = dirname(__DIR__) . '/Auth/Stubs/User.stub';
        $this->publishes([$source => app_path("User.php")], "model");
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
