<?php namespace tests\Kahire\ViewSets;

use Kahire\Tests\TestCase;
use Kahire\Tests\UseTestDatabase;
use Kahire\ViewSets\ModelViewSet;
use TestSubject\Foo;
use TestSubject\Http\Controllers\FooController;

class ModelViewSetTest extends TestCase {

    use UseTestDatabase;

    /**
     * @var ModelViewSet
     */
    public $viewSet;

    /**
     * @var array
     */
    public $validData;


    public function setUp()
    {
        parent::setUp();

        $this->app["config"]->set("debug", "true");

        $this->viewSet = FooController::class;

        $this->validData = [
            "string"  => "string",
            "integer" => 1
        ];
    }


    public function testCreate()
    {
        $this->post("foo", $this->validData);

        $this->assertResponseOk();
        $this->seeJsonEquals($this->validData);
        $this->seeJson();
    }


    public function testShow()
    {
        Foo::forceCreate($this->validData);

        $this->get("foo/1");
        $this->assertResponseOk();
        $this->seeJsonEquals($this->validData);
        $this->seeJson();
    }


    public function testIndex()
    {
        Foo::forceCreate($this->validData);

        $this->get("foo/");
        $this->assertResponseOk();
        $this->seeJsonEquals([ $this->validData ]);
        $this->seeJson();
    }


    /**
     * @group development
     */
    public function testUpdate()
    {
        Foo::forceCreate($this->validData);

        $update = [
            "integer" => 2
        ];

        $this->put("foo/1", $update);

        $update["string"] = $this->validData["string"];

        $this->assertResponseOk();
        $this->seeJsonEquals($update);
    }
}
