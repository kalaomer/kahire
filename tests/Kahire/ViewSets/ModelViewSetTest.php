<?php namespace tests\Kahire\ViewSets;

use Illuminate\Http\Response;
use Kahire\Tests\TestCase;
use Kahire\Tests\UseTestDatabase;
use TestSubject\Basic;

class ModelViewSetTest extends TestCase {

    use UseTestDatabase;

    /**
     * @var array
     */
    public $validData;


    public function setUp()
    {
        parent::setUp();

        $this->validData = [
            "string"  => "string",
            "integer" => 1
        ];
    }


    public function testCreate()
    {
        $this->post("basic", $this->validData);

        $this->assertResponseStatus(Response::HTTP_CREATED);
        $this->seeJsonEquals($this->validData);
        $this->seeJson();
    }


    public function testShow()
    {
        Basic::forceCreate($this->validData);

        $this->get("basic/1");
        $this->assertResponseOk();
        $this->seeJsonEquals($this->validData);
        $this->seeJson();
    }


    public function testIndex()
    {
        Basic::forceCreate($this->validData);

        $this->get("basic/");
        $this->assertResponseOk();
        $this->seeJsonEquals([ $this->validData ]);
        $this->seeJson();
    }


    public function testUpdate()
    {
        Basic::forceCreate($this->validData);

        $update = [
            "integer" => 2
        ];

        $this->put("basic/1", $update);

        $update["string"] = $this->validData["string"];

        $this->assertResponseOk();
        $this->seeJsonEquals($update);
    }
}
