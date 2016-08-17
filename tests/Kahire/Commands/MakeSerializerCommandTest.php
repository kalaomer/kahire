<?php namespace Kahire\Tests\Commands;

use Illuminate\Filesystem\Filesystem;
use Kahire\Tests\TestCase;
use Kahire\Commands\MakeSerializerCommand;

class MakeSerializerCommandTest extends TestCase {

    /**
     * @var MakeSerializerCommand
     */
    protected $command;

    public function setUp()
    {
        parent::setUp();

        $this->command = app(MakeSerializerCommand::class);
    }


    /**
     * @group development
     */
    public function testCreateSerializer()
    {
        $this->app["Illuminate\\Contracts\\Console\\Kernel"]->call("api:serializer", [
            "name" => "example",
            "fields" => "id:integer:null(true):max(10),".
                        "name:string,".
                        "user:User\\ParentSerializer(100):many"
        ]);

        $this->assertFileExists($this->command->getSerializerPath("example"));

        $this->app[Filesystem::class]->deleteDirectory($this->command->getSerializerPath());
    }
}