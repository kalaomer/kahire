<?php namespace Kahire\Serializers;

use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Kahire\Serializers\Fields\Field;

/**
 * Class Serializer
 * @package Kahire\Serializers
 * @method $this partial()
 * @method $this instance()
 * @method $this initialData()
 */
abstract class Serializer extends Field {

    /**
     * @return array
     */
    abstract public function getFields();


    protected $partial = false;

    protected $instance = null;

    protected $initialData = null;

    protected $writableFields = [ ];

    protected $readableFields = [ ];

    protected $fields = [ ];

    public $errors = [ ];

    protected $validatedData;


    public function __construct()
    {
        parent::__construct();

        $this->addAttributes("partial", "instance", "initialData");
        $this->fields = $this->getFields();
        $this->writableFields = iterator_to_array($this->getWritableFields());
        $this->readableFields = iterator_to_array($this->getReadableFields());
    }


    protected function getWritableFields()
    {
        /* @var $field Field */
        foreach ($this->fields as $field)
        {
            if ( ! $field->readOnly or $field->default != null )
            {
                yield $field;
            }
        }
    }

    protected function getReadableFields()
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

        return $value;
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
            $this->errors[]      = $e->getMessage();
        }

        if ( $this->errors and $raiseException )
        {
            throw new ValidationError($this->errors);
        }

        return ! (bool) $this->errors;
    }

}
