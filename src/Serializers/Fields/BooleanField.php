<?php namespace Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\Exceptions\ValueError;

class BooleanField extends Field {

    CONST TRUE_VALUES = [ "t", "T", "true", "True", "TRUE", "1", 1, true ];
    CONST FALSE_VALUES = [ "f", "F", "false", "False", "FALSE", "0", 0, false ];


    public function toRepresentation($value)
    {
        // Can not use is_array or array_search
        // Because they always return TRUE because of `true` value which is in TRUE_VALUES.
        foreach (self::TRUE_VALUES as $trueValue)
        {
            if ( $trueValue === $value )
            {
                return true;
            }
        }

        foreach (self::FALSE_VALUES as $falseValue)
        {
            if ( $falseValue === $value )
            {
                return false;
            }
        }

        return (bool) $value;
    }


    public function toInternalValue($value)
    {
        foreach (self::TRUE_VALUES as $trueValue)
        {
            if ( $trueValue === $value )
            {
                return true;
            }
        }

        foreach (self::FALSE_VALUES as $falseValue)
        {
            if ( $falseValue === $value )
            {
                return false;
            }
        }

        $this->fail("invalid");
    }

}