<?php namespace Kahire\Serializers\Fields;

use Kahire\src\Serializers\Fields\Exceptions\ValueError;

class BooleanField extends Field {

    CONST TRUE_VALUES = [ "t", "T", "true", "True", "TRUE", "1", 1, true ];
    CONST FALSE_VALUES = [ "f", "F", "false", "False", "FALSE", "0", 0, false ];


    public function toRepresentation($value)
    {
        if ( in_array($value, BooleanField::TRUE_VALUES) )
        {
            return true;
        }
        elseif ( in_array($value, BooleanField::FALSE_VALUES) )
        {
            return false;
        }

        return (bool) $value;
    }


    public function toInternalValue($value)
    {
        if ( in_array($value, BooleanField::TRUE_VALUES) )
        {
            return true;
        }
        elseif ( in_array($value, BooleanField::FALSE_VALUES) )
        {
            return false;
        }

        throw new ValueError($value);
    }

}