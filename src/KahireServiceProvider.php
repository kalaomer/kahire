<?php

namespace Kahire;

use Illuminate\Support\ServiceProvider;
use Kahire\Commands\MakeSerializerCommand;

/**
 * Class KahireServiceProvider.
 */
class KahireServiceProvider extends ServiceProvider
{
    /**
     * @return bool
     */
    public function isDeferred()
    {
        return false;
    }

    /**
     *
     */
    public function register()
    {
        $this->registerMiddleware();
        $this->registerSerializerGenerator();
    }

    private function registerMiddleware()
    {
        $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');
        $kernel->pushMiddleware('Kahire\Middleware\ValidationMiddleware');
    }

    private function registerSerializerGenerator()
    {
        $this->app->singleton('command.kahire.serializer', function($app) {
            return $app[MakeSerializerCommand::class];
        });

        $this->commands("command.kahire.serializer");
    }
}
