<?php namespace Kahire\Tests\Serializers;

use Kahire\Serializers\Fields\IntegerField;
use Kahire\Serializers\ListSerializer;
use Kahire\Serializers\Serializer;
use Kahire\Tests\TestCase;

class ListSerializerTest extends TestCase {

    /**
     * @var ListSerializer
     */
    public $serializer;


    public function setUp()
    {
        parent::setUp();

        $this->serializer = IntegerSerializer::generate()->many();
    }


    public function testValidate()
    {
        $inputData = [ [ "1" ], [ "2" ] ];
        $validData = [ [ 1 ], [ 2 ] ];

        $this->serializer->data($inputData)->isValid();

        $this->assertEquals($this->serializer->data(), $validData);
    }
}

class IntegerSerializer extends Serializer {

    public function generateFields()
    {
        return [
            "integer" => IntegerField::generate()
        ];
    }


    public function create($validatedData)
    {
    }


    public function update($instance, $validatedData)
    {
    }
}
