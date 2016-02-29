<?php

namespace Kahire\Tests;

use Illuminate\Filesystem\ClassFinder;
use Illuminate\Filesystem\Filesystem;

trait UseTestDatabase
{
    /**
     * @before
     */
    public function migrateDatabase()
    {
        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');

        $this->triggerMigrationFiles();
    }

    public function triggerMigrationFiles()
    {
        $fileSystem = new Filesystem;
        $classFinder = new ClassFinder;

        foreach ($fileSystem->files(TEST_DIR.'/database/migrations') as $file) {
            $fileSystem->requireOnce($file);
            $migrationClass = $classFinder->findClass($file);

            (new $migrationClass)->up();
        }
    }
}
