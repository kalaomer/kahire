<?php namespace Kahire\Tests\Serializers\Fields;

use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Kahire\Serializers\Fields\Field;
use Kahire\Tests\TestCase;

class FieldTestCase extends TestCase {

    public $validInputs = [ ];

    public $invalidInputs = [ ];

    public $outputs = [ ];

    public $fieldClass;

    /**
     * @var Field
     */
    public $field;


    public function setUp()
    {
        parent::setUp();

        $this->field = new $this->fieldClass();
    }


    public function testValidInputs()
    {
        foreach ($this->validInputs as $validInput => $validResponse)
        {
            $this->assertEquals($validResponse, $this->field->runValidation($validInput));
        }
    }


    public function testInvalidInputs()
    {
        foreach ($this->invalidInputs as $invalidInput)
        {
            try
            {
                $this->field->runValidation($invalidInput);
            }
            catch (ValidationError $e)
            {
                continue;
            }

            $this->fail("Validation exception is not thrown");
        }
    }


    public function testOutputs()
    {
        foreach ($this->outputs as $output => $outputResponse)
        {
            $this->assertEquals($outputResponse, $this->field->toRepresentation($output));
        }
    }
}
