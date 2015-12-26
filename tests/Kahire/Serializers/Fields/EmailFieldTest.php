<?php namespace Kahire\tests\Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\EmailField;
use Kahire\Serializers\Fields\Exceptions\ValidationError;

class EmailFieldTest extends \TestCase {

    /**
     * @var EmailField
     */
    public $field;


    public function setUp()
    {
        parent::setUp();

        $this->field = new EmailField();
    }


    public function testRunValidation()
    {
        $this->assertEquals("foo@bar.com", $this->field->runValidation("foo@bar.com"));
    }


    public function testRunValidationException()
    {
        $this->setExpectedException(ValidationError::class);
        $this->field->runValidation("not an email");
    }
}
