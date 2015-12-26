<?php namespace Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\ValidationRules\MinMaxTrait;

/**
 * Class IntegerField
 * @package Kahire\Serializers\Fields
 * @method $this min()
 * @method $this max()
 */
class IntegerField extends Field {

    use MinMaxTrait;

    protected $validationRules = [ "integer" ];

    protected $attributes = [ "min", "max" ];


    public function toInternalValue($value)
    {
        if ( is_numeric($value) )
        {
            return intval($value);
        }

        $this->fail("invalid");
    }


    public function toRepresentation($value)
    {
        return (int) $value;
    }


    public function getValidationRules()
    {
        return $this->getMinMaxValidationRules();
    }
}

