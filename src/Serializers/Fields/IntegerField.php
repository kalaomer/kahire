<?php namespace Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\Attributes\MaximumAttribute;
use Kahire\Serializers\Fields\Attributes\MinimumAttribute;

/**
 * Class IntegerField
 * @package Kahire\Serializers\Fields
 */
class IntegerField extends Field {

    use MinimumAttribute, MaximumAttribute;

    /**
     * @var array
     */
    protected $validationRules = [ "integer" ];


    /**
     * @param $value
     *
     * @return int
     * @throws Exceptions\ValidationError
     */
    public function toInternalValue($value)
    {
        if ( is_numeric($value) )
        {
            return intval($value);
        }

        $this->fail("invalid");
    }


    /**
     * @param $value
     *
     * @return int
     */
    public function toRepresentation($value)
    {
        return (int) $value;
    }

}

