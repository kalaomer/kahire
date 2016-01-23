<?php namespace Kahire\Tests;

use Illuminate\Contracts\Console\Kernel;
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

        $app->instance("path.storage", realpath(TEST_DIR . "/TestSubject/storage"));

        $app->register(KahireServiceProvider::class);
        $app->register(TestSubjectProvider::class);

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
