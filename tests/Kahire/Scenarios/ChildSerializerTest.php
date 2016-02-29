<?php

namespace Kahire\Tests\Scenarios;

use Kahire\Serializers\Fields\IntegerField;
use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\Serializer;
use Kahire\Tests\TestCase;

class ChildSerializerTest extends TestCase
{
    /**
     * @var Serializer
     */
    protected $serializer;

    public function setUp()
    {
        parent::setUp();

        $this->serializer = new FatherSerializer();
        $this->serializer->bind('father', null);
    }

    public function testRunValidation()
    {
        $validData = [
            'integer' => 1,
            'child'   => [
                'string'  => 'string',
                'integer' => 10,
            ],
        ];

        $this->assertEquals($validData, $this->serializer->runValidation($validData));
    }

    public function testChildFieldValidationClauses()
    {
        $validationRules = [
            'child'   => 'required',
            'integer' => 'integer|required',
        ];

        $this->assertEquals($this->serializer->getChildFieldValidationClauses(), $validationRules);
    }
}

class FatherSerializer extends Serializer
{
    public function generateFields()
    {
        return [
            'child'   => ChildSerializer::generate(),
            'integer' => IntegerField::generate(),
        ];
    }

    public function create($validatedData)
    {
        // TODO: Implement create() method.
    }

    public function update($instance, $validatedData)
    {
        // TODO: Implement update() method.
    }
}

class ChildSerializer extends Serializer
{
    public function generateFields()
    {
        return [
            'string'  => StringField::generate(),
            'integer' => IntegerField::generate(),
        ];
    }

    public function create($validatedData)
    {
        // TODO: Implement create() method.
    }

    public function update($instance, $validatedData)
    {
        // TODO: Implement update() method.
    }
}
