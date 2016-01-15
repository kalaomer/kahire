<?php namespace Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\Attributes\MaximumAttribute;
use Kahire\Serializers\Fields\Attributes\MinimumAttribute;

/**
 * Class IntegerField
 * @package Kahire\Serializers\Fields
 */
class IntegerField extends Field {

    use MinimumAttribute, MaximumAttribute;

    protected $validationRules = [ "integer" ];


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

}

