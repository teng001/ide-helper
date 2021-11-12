<?php


namespace Tengs\IdeHelper;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Tengs\IdeHelper\Commands\GenerateCommand;

/**
 * Class ServiceProvider.
 *
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Boot the provider.
     */
    public function boot()
    {
        $this->registerCommands();
    }


    /**
     * Register the provider.
     */
    public function register()
    {

    }

    /**
     * Register the Horizon Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCommand::class
            ]);
        }
        $this->commands([GenerateCommand::class]);
    }
}
