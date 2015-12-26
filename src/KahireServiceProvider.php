<?php namespace Kahire;

use Illuminate\Support\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: kalaomer
 * Date: 22.12.2015
 * Time: 00:20
 */
class KahireServiceProvider extends ServiceProvider {

    public function isDeferred()
    {
        return false;
    }


    public function register()
    {

    }

}