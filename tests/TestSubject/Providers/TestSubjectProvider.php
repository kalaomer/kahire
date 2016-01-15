<?php namespace TestSubject\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * This class set routes for testing.
 *
 * @package TestSubject
 */
class TestSubjectProvider extends ServiceProvider {

    public function isDeferred()
    {
        return false;
    }


    public function register()
    {

    }


    public function boot()
    {
        require TEST_DIR . '/TestSubject/Http/routes.php';
    }
}