<?php namespace Kahire\Serializers;

use Illuminate\Support\Facades\Validator;
use Kahire\Serializers\Fields\DataTypes\EmptyType;
use Kahire\Serializers\Fields\Exceptions\SkipField;
use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Kahire\Serializers\Fields\Field;

/**
 * Class Serializer
 * @package Kahire\Serializers
 * @method $this partial()
 * @method $this instance()
 * @method $this context()
 */
abstract class Serializer extends Field {

    /**
     * @return array
     */
    abstract public function getFields();


    abstract public function create($validatedData);


    abstract public function update($instance, $validatedData);


    protected $partial = false;

    protected $instance = null;

    protected $initialData = null;

    protected $context = [ ];

    protected $fields = [ ];

    public $errors = [ ];

    protected $validatedData;

    protected $_data;


    public function __construct()
    {
        parent::__construct();

        $this->addAttributes("partial", "instance", "context");
        $this->fields = $this->getFields();
        $this->setFields();
    }


    /**
     * @return ListSerializer
     */
    public function many()
    {
        return ListSerializer::generate()->child($this);
    }


    protected function setFields()
    {
        /* @var $field Field */
        foreach ($this->fields as $fieldName => $field)
        {
            $field->bind($fieldName, $this);
        }
    }


    public function getWritableFields()
    {
        /* @var $field Field */
        foreach ($this->fields as $field)
        {
            if ( ! $field->readOnly or ! EmptyType::isEmpty($field->default()) )
            {
                yield $field;
            }
        }
    }


    public function getReadableFields()
    {
        /* @var $field Field */
        foreach ($this->fields as $field)
        {
            if ( $field->writeOnly == false )
            {
                yield $field;
            }
        }
    }


    public function runValidation($data)
    {
        list( $isEmpty, $data ) = $this->validateEmptyValues($data);

        if ( $isEmpty )
        {
            return $data;
        }

        $value = $this->toInternalValue($data);
        $this->runValidators($value);
        $value = $this->validate($value);
        $this->runValidationClause($value);

        return $value;
    }

    public function runValidationClause($data)
    {
        $validationClauses = [];

        /* @var $field Field */
        foreach ($this->fields as $field)
        {
            $validationClauses[$field->source()] = $field->getValidationClause();
        }

        $validator = Validator::make($data, $validationClauses);

        if ( $validator->fails() )
        {
            throw new ValidationError($validator->errors()->all());
        }
    }


    public function toInternalValue($data)
    {
        if ( ! is_array($data) )
        {
            $this->fail("invalid");
        }

        $internalValue = [ ];
        $errors        = [ ];

        /* @var $field Field */
        foreach ($this->getWritableFields() as $field)
        {
            $validatedValue = null;
            $primitiveValue = $field->getValue($data);

            try
            {
                $validatedValue       = $field->runValidation($primitiveValue);
                $validationMethodName = $this->getValidationMethodName($field->getFieldName());

                if ( method_exists($this, $validationMethodName) )
                {
                    $validatedValue = call_user_func([ $this, $validationMethodName ], $validatedValue);
                }
            }
            catch (ValidationError $e)
            {
                $errors[$field->getFieldName()] = $e->getErrors();
            }
            catch (SkipField $e)
            {
                continue;
            }

            $this->setValue($internalValue, $field->sourceAttr(), $validatedValue);
        }

        if ( $errors )
        {
            throw new ValidationError($errors);
        }

        return $internalValue;
    }


    protected function setValue(&$data, $keys, $value)
    {
        if ( $keys == [ ] )
        {
            return array_merge($data, $value);
        }

        $last = array_pop($keys);
        foreach ($keys as $key)
        {
            if ( ! array_key_exists($key, $data) )
            {
                $data[$key] = [ ];
            }

            $data = &$data[$key];
        }

        $data[$last] = $value;
    }


    public function toRepresentation($instance)
    {
        $response = [ ];

        /* @var $field Field */
        foreach ($this->getReadableFields() as $field)
        {
            $attribute = null;

            try
            {
                $attribute = $field->getAttribute($instance);
            }
            catch (SkipField $e)
            {
            }

            if ( $attribute === null )
            {
                $response[$field->getFieldName()] = null;
            }
            else
            {
                $response[$field->fieldName] = $field->toRepresentation($attribute);
            }
        }

        return $response;
    }


    protected function getValidationMethodName($fieldName)
    {
        $fieldName = preg_replace_callback('/\_([a-z])/', function ($matches)
        {
            return strtoupper($matches[1]);
        }, $fieldName);

        return "validate" . ucfirst($fieldName);
    }


    public function validate($attributes)
    {
        return $attributes;
    }


    public function isValid($raiseException = false)
    {
        try
        {
            $this->validatedData = $this->runValidation($this->initialData);
        }
        catch (ValidationError $e)
        {
            $this->validatedData = [ ];
            $this->errors        = $e->getErrors();
        }

        if ( $this->errors !== [ ] and $raiseException )
        {
            throw new ValidationError($this->errors);
        }

        return ! $this->hasError();
    }


    public function hasError()
    {
        return $this->errors !== [ ];
    }


    /**
     * @param array|null $initialData
     *
     * @return $this|array|null
     * @throws \AssertionError
     */
    public function data(array $initialData = null)
    {
        if ( $initialData !== null )
        {
            $this->initialData = $initialData;

            return $this;
        }

        if ( $this->initialData and ! $this->validatedData === null )
        {
            throw new \AssertionError("Call .isValid() before calling data.");
        }

        if ( $this->_data === null )
        {

            if ( $this->instance and ! $this->hasError() )
            {
                $this->_data = $this->toRepresentation($this->instance);
            }
            elseif ( $this->validatedData and ! $this->hasError() )
            {
                $this->_data = $this->toRepresentation($this->validatedData);
            }
            else
            {
                $this->_data = $this->initialData;
            }
        }

        return $this->_data;
    }


    public function save(array $data = [ ])
    {
        $validatedData = array_merge($this->validatedData, $data);

        if ( $this->instance )
        {
            $this->instance = $this->update($this->instance, $validatedData);
        }
        else
        {
            $this->instance = $this->create($validatedData);
        }

        return $this->instance;
    }

}
