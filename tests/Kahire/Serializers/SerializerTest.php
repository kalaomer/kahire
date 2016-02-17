<?php namespace Kahire\Tests\Serializers;

use Kahire\Serializers\Fields\BooleanField;
use Kahire\Serializers\Fields\EmailField;
use Kahire\Serializers\Fields\IntegerField;
use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\Serializer;
use Kahire\Tests\TestCase;

class SerializerTest extends TestCase {

    /**
     * @var Serializer
     */
    public $serializer;


    public function setUp()
    {
        parent::setUp();

        $this->serializer = new FooSerializer();
        $this->serializer->bind("foo_serializer", null);
    }


    public function testWritableField()
    {
        $writableFields = iterator_to_array($this->serializer->getWritableFields());
        $this->assertEquals(count($writableFields), 5);
    }


    public function testReadableField()
    {
        $readableFields = iterator_to_array($this->serializer->getReadableFields());
        $this->assertEquals(count($readableFields), 4);
    }


    public function testIsValid()
    {
        $validData = [
            "status"       => false,
            "small_number" => 10,
            "name"         => "foo",
            "email"        => "foo@bar.com"
        ];

        $this->serializer->data($validData);

        $this->assertEquals(true, $this->serializer->isValid());
        $this->assertEquals(false, $this->serializer->hasError());

        $this->serializer->data([ ]);
        $this->assertEquals(false, $this->serializer->isValid());
        $this->assertEquals(true, $this->serializer->hasError());
    }


    public function testData()
    {
        $validData = [
            "status"       => "1",
            "small_number" => 10,
            "name"         => "foo",
            "email"        => "foo@bar.com"
        ];

        $output = [
            "small_number" => 10,
            "name"         => "foo",
            "email"        => "foo@bar.com",
            "big_number"   => 100
        ];

        $this->serializer->data($validData);

        $this->assertEquals(true, $this->serializer->isValid());
        $this->assertEquals($output, $this->serializer->data());
    }


    public function testSource()
    {
        $serializer = new SourceSerializer();
        $validData  = [
            "string" => "string"
        ];

        $serializer->data($validData)->isValid(true);

        $this->assertEquals($serializer->getValidatedData(), [ "parent" => [ "child" => "string" ] ]);
    }

}

class FooSerializer extends Serializer {

    public function create($validatedData)
    {
        // TODO: Implement create() method.
    }


    public function update($instance, $validatedData)
    {
        // TODO: Implement update() method.
    }


    public function generateFields()
    {
        return [
            "big_number"   => IntegerField::generate()->readOnly(true)->default(100),
            "status"       => BooleanField::generate()->writeOnly(true),
            "small_number" => IntegerField::generate()->max(10),
            "name"         => StringField::generate(),
            "email"        => EmailField::generate()
        ];
    }
}

class SourceSerializer extends Serializer {

    public function update($instance, $validatedData)
    {
        // TODO: Implement update() method.
    }


    public function create($validatedData)
    {
        // TODO: Implement create() method.
    }


    public function generateFields()
    {
        return [
            "string" => StringField::generate()->source("parent.child")
        ];
    }
}