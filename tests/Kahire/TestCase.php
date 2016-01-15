<?php namespace Kahire\Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Kahire\KahireServiceProvider;
use TestSubject\Providers\TestSubjectProvider;

abstract class TestCase extends BaseTestCase {

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';


    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require TEST_DIR . "/../vendor/laravel/laravel/bootstrap/app.php";

        $app->register(KahireServiceProvider::class);
        $app->register(TestSubjectProvider::class);

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }


    public function getApp()
    {
        $app = new Application(realpath(TEST_DIR . '/TestSubject'));

        $app->singleton(\Illuminate\Contracts\Http\Kernel::class, \App\Http\Kernel::class);

        $app->singleton(\Illuminate\Contracts\Console\Kernel::class, \TestSubject\Console\Kernel::class);

        $app->singleton(ExceptionHandler::class, Handler::class);

        return $app;
    }
}
