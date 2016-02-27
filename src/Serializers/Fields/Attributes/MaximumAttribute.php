<?php namespace Kahire\Serializers\Fields\Attributes;

/**
 * Class MaximumAttribute
 * @package Kahire\Serializers\Fields\Attributes
 */
trait MaximumAttribute {

    /**
     * @var null
     */
    protected $max = null;


    /**
     * @param int $value
     *
     * @return $this
     */
    public function max($value = null)
    {
        if ( $value !== null )
        {
            if ( ! is_integer($value) )
            {
                throw new \InvalidArgumentException("value must be integer.");
            }

            $this->max = $value;

            return $this;
        }

        return $this->max;
    }


    /**
     * @return array
     */
    public function getMaximumValidationRule()
    {
        if ( $this->max !== null )
        {
            return [ "max" => $this->max ];
        }

        return [ ];
    }
}