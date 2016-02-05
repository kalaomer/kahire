<?php namespace Kahire\Tests\Serializers\Fields;

use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Kahire\Serializers\Fields\IntegerField;

class IntegerFieldTest extends FieldTestCase {

    /**
     * @var IntegerField
     */
    public $field;

    public $fieldClass = IntegerField::class;

    public $validInputs = [
        [ "123", 123 ],
        [ 123, 123 ]
    ];

    public $invalidInputs = [
        "wrong input"
    ];


    public function testMinimumException()
    {
        $this->field->min(10);

        $this->setExpectedException(ValidationError::class);
        $this->field->runValidation(1);
    }


    public function testMaximumException()
    {
        $this->field->max(10);

        $this->setExpectedException(ValidationError::class);
        $this->field->runValidation(100);
    }
}
