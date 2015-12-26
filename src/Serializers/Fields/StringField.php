<?php namespace Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\ValidationRules\MinMaxTrait;

/**
 * Class StringField
 * @package Kahire\Serializers\Fields
 * @method $this min()
 * @method $this max()
 */
class StringField extends Field {

    use MinMaxTrait;

    protected $validationRules = [ "string" ];

    protected $attributes = [ "min", "max" ];


    public function toRepresentation($value)
    {
        return (string) $value;
    }


    public function toInternalValue($value)
    {
        return (string) $value;
    }


    public function getValidationRules()
    {
        return $this->getMinMaxValidationRules();
    }

}