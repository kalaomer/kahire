<?php namespace Kahire;

use Illuminate\Support\ServiceProvider;

class KahireServiceProvider extends ServiceProvider {

    public function isDeferred()
    {
        return false;
    }


    public function register()
    {
        $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');
        $kernel->pushMiddleware('Kahire\Middleware\ValidationMiddleware');
    }

}