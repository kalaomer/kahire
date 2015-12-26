<?php

namespace Kahire\tests\Kahire\Serializers;

use Kahire\Serializers\Fields\EmailField;
use Kahire\Serializers\Fields\IntegerField;
use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\Serializer;

class SerializerTest extends \TestCase {

    /**
     * @var Serializer
     */
    public $serializer;


    public function setUp()
    {
        parent::setUp();

        $this->serializer = new class extends Serializer {

            public function toRepresentation($value)
            {
                return $value;
            }


            public function toInternalValue($value)
            {
                return $value;
            }


            public function getFields(): array
            {
                return [
                    "big_number"    => IntegerField::create()->readOnly(true),
                    "medium_number" => IntegerField::create()->writeOnly(true),
                    "small_number"  => IntegerField::create(),
                    "name"          => StringField::create(),
                    "email"         => EmailField::create()
                ];
            }
        };
    }


    public function testWritableField()
    {
        $writableFields = \PHPUnit_Framework_Assert::readAttribute($this->serializer, "writableFields");
        $this->assertEquals(count($writableFields), 4);
    }


    public function testReadableField()
    {
        $readableFields = \PHPUnit_Framework_Assert::readAttribute($this->serializer, "readableFields");
        $this->assertEquals(count($readableFields), 4);
    }
}
