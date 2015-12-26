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

    abstract public function getFields(): array;


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
        $this->setReadableAndWritableFields();
    }


    protected function setReadableAndWritableFields()
    {
        /* @var $field Field */
        foreach ($this->fields as $field)
        {
            if ( ! $field->readOnly or $field->default != null )
            {
                $this->writableFields[] = $field;
            }

            if ( $field->writeOnly == false )
            {
                $this->readableFields[] = $field;
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
