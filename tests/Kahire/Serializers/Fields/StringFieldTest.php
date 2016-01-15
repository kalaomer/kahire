<?php namespace Kahire\Tests\Serializers\Fields;

use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Kahire\Serializers\Fields\StringField;

class StringFieldTest extends FieldTestCase {

    /**
     * @var StringField
     */
    public $field;

    public $fieldClass = StringField::class;

    public $validInputs = [
        "Foo" => "Foo",
        123   => "123"
    ];

    public $outputs = [
        "Foo" => "Foo",
        123   => "123"
    ];


    public function testMinimumException()
    {
        $this->setExpectedException(ValidationError::class);
        $this->field->min(100)->runValidation("small text");
    }
}
