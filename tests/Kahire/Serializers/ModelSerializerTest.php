<?php namespace Kahire\Tests\Serializers;

use Kahire\Serializers\Fields\IntegerField;
use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\ModelSerializer;
use Kahire\Tests\TestCase;
use Kahire\Tests\UseTestDatabase;
use TestSubject\Foo;

class ModelSerializerTest extends TestCase {

    use UseTestDatabase;

    /**
     * @var ModelSerializer
     */
    public $serializer;


    public function setUp()
    {
        parent::setUp();

        $this->serializer = new class extends ModelSerializer {

            public $model = Foo::class;


            public function getFields()
            {
                return [
                    "string"  => StringField::generate(),
                    "integer" => IntegerField::generate()
                ];
            }
        };
    }


    public function testCreate()
    {
        $validData = [
            "string"  => "string",
            "integer" => 1
        ];

        $this->serializer->data($validData)->isValid();
        $this->serializer->save();

        $this->assertEquals($validData, Foo::all("string", "integer")->last()->toArray());
    }


    public function testUpdate()
    {
        $validData = [
            "string"  => "string",
            "integer" => 1
        ];

        $instance          = new Foo();
        $instance->string  = "string";
        $instance->integer = 1;
        $instance->save();

        $validUpdate = [
            "string" => "new string"
        ];

        $validData["string"] = $validUpdate["string"];

        $this->serializer->instance($instance)->data($validUpdate)->partial(true);
        $this->assertEquals(true, $this->serializer->isValid());

        $this->serializer->save();

        $this->assertEquals($validData,
            Foo::select("string", "integer")->where("id", $instance->id)->first()->toArray());
    }
}
