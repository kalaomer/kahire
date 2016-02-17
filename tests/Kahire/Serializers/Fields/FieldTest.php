<?php namespace packages\Kahire\tests\Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\DataTypes\EmptyType;
use Kahire\Serializers\Fields\Exceptions\AttributeError;
use Kahire\Serializers\Fields\Exceptions\SkipField;
use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Kahire\Serializers\Fields\Field;
use Kahire\Serializers\Serializer;
use Kahire\Tests\TestCase;

class FieldTest extends TestCase {

    /**
     * @var Field
     */
    public $field;


    public function setUp()
    {
        parent::setUp();

        $this->field = new BaseField();
        $serializer  = new BaseSerializer();

        $this->field->bind("foo", $serializer);
    }


    public function testAttributes()
    {
        $this->field->allowNull(true);
        $this->field->required(false);
        $this->field->source("foo");

        $this->assertEquals($this->field->allowNull(), true);
        $this->assertEquals($this->field->required(), false);
        $this->assertEquals($this->field->source(), "foo");
    }


    public function testDefault()
    {
        $this->field->required(false)->allowNull(true)->default("foo");
        $this->assertEquals(null, $this->field->runValidation(null));
        $this->assertEquals("foo", $this->field->runValidation(EmptyType::get()));

        $this->field->default(new DefaultValue());

        $this->assertEquals($this->field->getFieldName(), $this->field->runValidation(EmptyType::get()));
    }


    public function testToInitialValue()
    {
        $this->assertEquals(123, $this->field->runValidation("123"));
        $this->assertEquals(123, $this->field->runValidation(123));
    }


    public function testToRepresentation()
    {
        $this->assertEquals(123, $this->field->toRepresentation("123"));
        $this->assertEquals(123, $this->field->toRepresentation(123));
    }


    public function testValidators()
    {
        $this->field->validators([
            new Validator()
        ]);

        $this->setExpectedException(ValidationError::class);
        $this->field->runValidation(100);
    }


    public function testValidationRulesStringRule()
    {
        $this->field->required(false)->addValidationRules([ "integer", "min:100" ]);

        $this->assertEquals($this->field->getValidationClause(), "integer|min:100");
    }


    public function testValidationRulesKeyRule()
    {
        $this->field->required(false)->addValidationRules([ "integer", "max" => 100 ]);

        $this->assertEquals($this->field->getValidationClause(), "integer|max:100");
    }


    public function testBind()
    {
        $parent = new BaseSerializer();
        $this->field->bind("foo", $parent);

        $parentAttribute = \PHPUnit_Framework_Assert::readAttribute($this->field, "parent");
        $rootAttribute   = \PHPUnit_Framework_Assert::readAttribute($this->field, "root");

        $this->assertEquals("foo", $this->field->getFieldName());
        $this->assertEquals("foo", $this->field->source());
        $this->assertEquals([ "foo" ], $this->field->sourceAttr());
        $this->assertEquals($parent, $parentAttribute);
        $this->assertEquals($parent, $rootAttribute);
    }


    public function testGetValue()
    {
        $values = [
            "foo" => "acme",
            "bar" => "good"
        ];

        $this->assertEquals($values["foo"], $this->field->getValue($values));
        $this->assertEquals(EmptyType::get(), $this->field->getValue([ ]));
        $this->assertEquals(null, $this->field->getValue([ "foo" => null ]));
    }


    public function testGetAttribute()
    {
        $instance = [
            "foo" => "acme",
            "bar" => [
                "key" => "value"
            ]
        ];

        $this->assertEquals($instance["foo"], $this->field->getAttribute($instance));

        $this->field->source("bar.key")->bind("key", null);

        $this->assertEquals($instance["bar"]["key"], $this->field->getAttribute($instance));
    }


    public function testGetAttributeSkipField()
    {
        $this->setExpectedException(SkipField::class);
        $this->field->required(false);

        $this->field->getAttribute([ ]);
    }


    public function testGetAttributeAttributeError()
    {
        $parent = clone $this->field;
        $this->field->bind("foo", $parent);

        $this->setExpectedException(AttributeError::class);

        $this->field->getAttribute([ ]);
    }
}

class BaseField extends Field {

    public function toInternalValue($value)
    {
        return $value;
    }


    public function toRepresentation($value)
    {
        return $value;
    }
}

class DefaultValue {

    /**
     * @var Field
     */
    public $field;


    public function setContext(Field $field)
    {
        $this->field = $field;
    }


    public function __invoke()
    {
        return $this->field->getFieldName();
    }
}

class Validator {

    /**
     * @var Field
     */
    public $field;


    public function setContext(Field $field)
    {
        $this->field = $field;
    }


    public function __invoke($value)
    {
        if ( $value > 10 )
        {
            throw new ValidationError("number is too high");
        }

        return $value;
    }
}

class BaseSerializer extends Serializer {

    public function generateFields()
    {
        return [ ];
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