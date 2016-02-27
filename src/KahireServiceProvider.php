<?php namespace Kahire;

use Illuminate\Support\ServiceProvider;

/**
 * Class KahireServiceProvider
 * @package Kahire
 */
class KahireServiceProvider extends ServiceProvider {

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
        $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');
        $kernel->pushMiddleware('Kahire\Middleware\ValidationMiddleware');
    }

}