<?php namespace Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\Attributes\MaximumAttribute;
use Kahire\Serializers\Fields\Attributes\MinimumAttribute;

/**
 * Class StringField
 * @package Kahire\Serializers\Fields
 */
class StringField extends Field {

    use MinimumAttribute, MaximumAttribute;

    /**
     * @var array
     */
    protected $validationRules = [ "string" ];


    /**
     * @param $value
     *
     * @return string
     */
    public function toRepresentation($value)
    {
        return (string) $value;
    }


    /**
     * @param $value
     *
     * @return string
     */
    public function toInternalValue($value)
    {
        return (string) $value;
    }

}