<?php

namespace Chatify;

use Chatify\Console\InstallCommand;
use Chatify\Console\PublishCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ChatifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('ChatifyMessenger', function () {
            return new ChatifyMessenger;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Load Views, Migrations, Routes
        $this->loadViewsFrom(__DIR__ . '/views', 'Chatify');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadRoutes();

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                PublishCommand::class,
            ]);
            $this->setPublishes();
        }
    }

    /**
     * Set the files that can be published by users.
     *
     * @return void
     */
    protected function setPublishes()
    {
        // Load avatar folder from package config
        $config = include(__DIR__ . '/config/chatify.php');
        $userAvatarFolder = $config['user_avatar']['folder'];

        // Publish config
        $this->publishes([
            __DIR__ . '/config/chatify.php' => config_path('chatify.php'),
        ], 'chatify-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations'),
        ], 'chatify-migrations');

        // Publish models
        $isV8 = explode('.', app()->version())[0] >= 8;
        $this->publishes([
            __DIR__ . '/Models' => app_path($isV8 ? 'Models' : ''),
        ], 'chatify-models');

        // Publish controllers
        $this->publishes([
            __DIR__ . '/Http/Controllers' => app_path('Http/Controllers/vendor/Chatify'),
        ], 'chatify-controllers');

        // Publish views
        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/Chatify'),
        ], 'chatify-views');

        // Publish assets (CSS, JS, Images)
        $this->publishes([
            __DIR__ . '/assets/css' => public_path('css/chatify'),
            __DIR__ . '/assets/js' => public_path('js/chatify'),
            __DIR__ . '/assets/imgs' => storage_path("app/public/{$userAvatarFolder}"),
        ], 'chatify-assets');
    }

    /**
     * Load web routes for Chatify.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        Route::group($this->routesConfigurations(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        });
    }

    /**
     * Routes configuration options.
     *
     * @return array
     */
    private function routesConfigurations()
    {
        return [
            'prefix' => config('chatify.routes.prefix', 'chatify'),
            'namespace' => config('chatify.routes.namespace', 'Chatify\Http\Controllers'),
            'middleware' => config('chatify.routes.middleware', ['web', 'auth']),
        ];
    }
}
