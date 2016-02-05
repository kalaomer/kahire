<?php namespace Kahire\Tests\Serializers\Fields;

use Kahire\Serializers\Fields\DateTimeField;
use Kahire\Serializers\Fields\Exceptions\ValidationError;

class DateTimeFieldTest extends FieldTestCase {

    public $fieldClass = DateTimeField::class;

    /**
     * @var DateTimeField
     */
    public $field;

    public $invalidInputs = [
        "1",
        false
    ];


    public function getValidInputs()
    {
        return [
            [ "2010-10-10 10:10:10", "2010-10-10 10:10:10" ],
            [ new \DateTime("10 december 2015"), new \DateTime("10 december 2015") ]
        ];
    }


    public function getOutputs()
    {
        return [
            [ "2010-10-10 10:10:10", "2010-10-10 10:10:10" ],
            [ new \DateTime("10 december 2015"), "2015-12-10 00:00:00" ],
            [ 0, false ]
        ];
    }


    public function testAfter()
    {
        $this->field->after(new \DateTime("now"));
        $this->field->runValidation(new \DateTime("1 december 2040"));

        $this->setExpectedException(ValidationError::class);
        $this->field->runValidation(new \DateTime("1 december 1990"));
    }

    public function testBefore()
    {
        $this->field->before(new \DateTime("now"));
        $this->field->runValidation(new \DateTime("1 december 1990"));

        $this->setExpectedException(ValidationError::class);
        $this->field->runValidation(new \DateTime("1 december 2040"));
    }
}
