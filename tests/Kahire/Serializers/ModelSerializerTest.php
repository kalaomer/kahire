<?php namespace Kahire\Tests\Serializers;

use Kahire\Serializers\ModelSerializer;
use Kahire\Tests\TestCase;
use Kahire\Tests\UseTestDatabase;
use TestSubject\Basic;
use TestSubject\Http\Serializers\ArticleSerializer;
use TestSubject\Http\Serializers\BasicSerializer;

class ModelSerializerTest extends TestCase {

    use UseTestDatabase;

    /**
     * @var ModelSerializer
     */
    public $basicSerializer;

    /**
     * @var ModelSerializer
     */
    public $articleSerializer;


    public function setUp()
    {
        parent::setUp();

        $this->basicSerializer   = new BasicSerializer();
        $this->articleSerializer = new ArticleSerializer();
    }


    public function testCreate()
    {
        $validData = [
            "string"  => "string",
            "integer" => 1
        ];

        $this->basicSerializer->data($validData)->isValid();
        $this->basicSerializer->save();

        $this->assertEquals($validData, Basic::all("string", "integer")->last()->toArray());
    }


    public function testUpdate()
    {
        $validData = [
            "string"  => "string",
            "integer" => 1
        ];

        $instance          = new Basic();
        $instance->string  = "string";
        $instance->integer = 1;
        $instance->save();

        $validUpdate = [
            "string" => "new string"
        ];

        $validData["string"] = $validUpdate["string"];

        $this->basicSerializer->instance($instance)->data($validUpdate)->partial(true);
        $this->assertEquals(true, $this->basicSerializer->isValid());

        $this->basicSerializer->save();

        $this->assertEquals($validData,
            Basic::select("string", "integer")->where("id", $instance->id)->first()->toArray());
    }


    public function testGetOneToOneFields()
    {
        $oneToOneFields = [
            $this->articleSerializer->getFields()["author"]
        ];

        $this->assertEquals($this->articleSerializer->getOneToOneRelations(), $oneToOneFields);
    }


    public function testOneToManyFields()
    {
        $oneToManyFields = [
            $this->articleSerializer->getFields()["tags"]
        ];

        $this->assertEquals($this->articleSerializer->getOneToManyRelations(), $oneToManyFields);
    }


    public function testBaseFields()
    {
        $baseFields = [
            $this->articleSerializer->getFields()["title"]
        ];

        $this->assertEquals($this->articleSerializer->getBaseFields(), $baseFields);
    }
}
