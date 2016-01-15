<?php namespace Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\Attributes\MaximumAttribute;
use Kahire\Serializers\Fields\Attributes\MinimumAttribute;

/**
 * Class StringField
 * @package Kahire\Serializers\Fields
 */
class StringField extends Field {

    use MinimumAttribute, MaximumAttribute;

    protected $validationRules = [ "string" ];


    public function toRepresentation($value)
    {
        return (string) $value;
    }


    public function toInternalValue($value)
    {
        return (string) $value;
    }

}