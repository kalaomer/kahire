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


    public function getValidInputs()
    {
        return $this->validInputs;
    }


    public function getInvalidInputs()
    {
        return $this->invalidInputs;
    }


    public function getOutputs()
    {
        return $this->outputs;
    }


    public function testValidInputs()
    {
        foreach ($this->getValidInputs() as $validInput => $validResponse)
        {
            $this->assertEquals($validResponse, $this->field->runValidation($validInput));
        }
    }


    public function testInvalidInputs()
    {
        foreach ($this->getInvalidInputs() as $invalidInput)
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
        foreach ($this->getOutputs() as $output => $outputResponse)
        {
            $this->assertEquals($outputResponse, $this->field->toRepresentation($output));
        }
    }
}
