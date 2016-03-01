<?php

namespace Kahire\Serializers;

use ArrayAccess;
use AssertionError;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Kahire\Serializers\Fields\DataTypes\EmptyType;
use Kahire\Serializers\Fields\Exceptions\SkipField;
use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Kahire\Serializers\Fields\Field;

/**
 * Class Serializer.
 * @method $this partial()
 * @method $this instance(array $instance)
 * @method $this context()
 * @method $this data(array $initialData)
 */
abstract class Serializer extends Field
{
    /**
     * @return array
     */
    abstract public function generateFields();

    /**
     * @param $validatedData
     *
     * @return mixed
     */
    abstract public function create($validatedData);

    /**
     * @param $instance
     * @param $validatedData
     *
     * @return mixed
     */
    abstract public function update($instance, $validatedData);

    /**
     * @var bool
     */
    protected $partial = false;

    /**
     * @var null
     */
    protected $instance = null;

    /**
     * @var null
     */
    protected $initialData = null;

    /**
     * @var array
     */
    protected $context = [];

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @var
     */
    protected $validatedData;

    /**
     * @var
     */
    protected $data;

    /**
     * Serializer constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addAttributes('partial', 'context');
        $this->fields = array_merge($this->generateFields(), $this->generateAppendedFields());
        $this->setFields();
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return ListSerializer
     */
    public function many()
    {
        return ListSerializer::generate()->child($this);
    }

    /**
     *
     */
    protected function setFields()
    {
        /* @var $field Field */
        foreach ($this->fields as $fieldName => $field) {
            $field->bind($fieldName, $this);
        }
    }

    /**
     * @return \Generator
     */
    public function getWritableFields()
    {
        /* @var $field Field */
        foreach ($this->fields as $field) {
            if (! $field->readOnly or ! EmptyType::isEmpty($field->default())) {
                yield $field;
            }
        }
    }

    /**
     * @return \Generator
     */
    public function getReadableFields()
    {
        /* @var $field Field */
        foreach ($this->fields as $field) {
            if ($field->writeOnly == false) {
                yield $field;
            }
        }
    }

    /**
     * @param $data
     *
     * @return array|mixed
     * @throws SkipField
     * @throws ValidationError
     */
    public function runValidation($data)
    {
        list($isEmpty, $data) = $this->validateEmptyValues($data);

        if ($isEmpty) {
            return $data;
        }

        $value = $this->toInternalValue($data);
        $this->runValidators($value);
        $value = $this->validate($value);
        $this->runValidationClause($value);

        return $value;
    }

    /**
     * @return array
     */
    public function getChildFieldValidationClauses()
    {
        $validationClauses = [];

        /* @var $field Field */
        foreach ($this->fields as $field) {
            $validationClauses[$field->source()] = $field->getValidationClause();
        }

        return $validationClauses;
    }

    /**
     * @param $data
     *
     * @throws ValidationError
     */
    public function runValidationClause($data)
    {
        $validator = Validator::make($data, $this->getChildFieldValidationClauses());

        if ($validator->fails()) {
            throw new ValidationError($validator->errors()->all());
        }
    }

    /**
     * @param $data
     *
     * @return array
     * @throws ValidationError
     */
    public function toInternalValue($data)
    {
        if (! is_array($data)) {
            $this->fail('invalid');
        }

        $internalValue = [];
        $errors = [];

        /* @var $field Field */
        foreach ($this->getWritableFields() as $field) {
            $validatedValue = null;
            $primitiveValue = $field->getValue($data);

            try {
                $validatedValue = $field->runValidation($primitiveValue);
                $validationMethodName = $this->getValidationMethodName($field->getFieldName());

                if (method_exists($this, $validationMethodName)) {
                    $validatedValue = call_user_func([$this, $validationMethodName], $validatedValue);
                }
            } catch (ValidationError $e) {
                $errors[$field->getFieldName()] = $e->getErrors();
            } catch (SkipField $e) {
                continue;
            }

            $this->setValue($internalValue, $field->sourceAttr(), $validatedValue);
        }

        if ($errors) {
            throw new ValidationError($errors);
        }

        return $internalValue;
    }

    /**
     * @param $data
     * @param $keys
     * @param $value
     *
     * @return array
     */
    protected function setValue(&$data, $keys, $value)
    {
        if ($keys == []) {
            return $data = array_merge($data, $value);
        }

        $last = array_pop($keys);
        foreach ($keys as $key) {
            if (! array_key_exists($key, $data)) {
                $data[$key] = [];
            }

            $data = &$data[$key];
        }

        $data[$last] = $value;
    }

    /**
     * @param $instance
     *
     * @return array
     * @throws Fields\Exceptions\AttributeError
     */
    public function toRepresentation($instance)
    {
        $response = [];

        /* @var $field Field */
        foreach ($this->getReadableFields() as $field) {
            $attribute = null;

            try {
                $attribute = $field->getAttribute($instance);
            } catch (SkipField $e) {
            }

            if ($attribute === null) {
                $response[$field->getFieldName()] = null;
            } else {
                $response[$field->fieldName] = $field->toRepresentation($attribute);
            }
        }

        return $response;
    }

    /**
     * @param $fieldName
     *
     * @return string
     */
    protected function getValidationMethodName($fieldName)
    {
        $fieldName = preg_replace_callback('/\_([a-z])/', function ($matches) {
            return strtoupper($matches[1]);
        }, $fieldName);

        return 'validate'.ucfirst($fieldName);
    }

    /**
     * @param $attributes
     *
     * @return mixed
     */
    public function validate($attributes)
    {
        return $attributes;
    }

    /**
     * @param bool $raiseException
     *
     * @return bool
     * @throws ValidationError
     */
    public function isValid($raiseException = false)
    {
        try {
            $this->validatedData = $this->runValidation($this->initialData);
        } catch (ValidationError $e) {
            $this->validatedData = [];
            $this->errors = $e->getErrors();
        }

        if ($this->errors !== [] and $raiseException) {
            throw new ValidationError($this->errors);
        }

        return ! $this->hasError();
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return $this->errors !== [];
    }

    protected function setDataAttribute(array $initialData)
    {
        $this->initialData = $initialData;

        return $this;
    }
    protected function getDataAttribute()
    {
        if ($this->initialData and ! $this->validatedData === null) {
            throw new AssertionError('Call .isValid() before calling data.');
        }

        if ($this->data === null) {
            if ($this->instance and ! $this->hasError()) {
                $this->data = $this->toRepresentation($this->instance);
            } elseif ($this->validatedData and ! $this->hasError()) {
                $this->data = $this->toRepresentation($this->validatedData);
            } else {
                $this->data = $this->initialData;
            }
        }

        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return mixed|null
     */
    public function save(array $data = [])
    {
        $validatedData = array_merge($this->validatedData, $data);

        if ($this->instance) {
            $this->instance = $this->update($this->instance, $validatedData);
        } else {
            $this->instance = $this->create($validatedData);
        }

        return $this->instance;
    }

    /**
     * @return mixed
     */
    public function getValidatedData()
    {
        return $this->validatedData;
    }

    /**
     * @return array
     */
    protected function generateAppendedFields()
    {
        $fields = [];

        foreach (get_class_methods(get_called_class()) as $method) {
            if (preg_match('/^generate[A-Za-z0-9]+Field$/', $method)) {
                $fields = array_merge($fields, call_user_func([$this, $method]));
            }
        }

        return $fields;
    }

    /**
     * @return null
     */
    protected function getInstanceAttribute()
    {
        return $this->instance;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    protected function setInstanceAttribute($value)
    {
        if (! is_array($value) && ! $value instanceof ArrayAccess) {
            throw new InvalidArgumentException('instance must be an array or implements ArrayAccess');
        }

        $this->instance = $value;

        return $this;
    }
}
