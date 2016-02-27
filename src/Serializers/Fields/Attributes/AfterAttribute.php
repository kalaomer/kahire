<?php namespace Kahire\Serializers\Fields\Attributes;

use DateTime;

/**
 * Class AfterAttribute
 * @package Kahire\Serializers\Fields\Attributes
 */
trait AfterAttribute {

    /**
     * @var string
     */
    protected $after;


    /**
     * @param null $value
     *
     * @return $this|string
     */
    public function after($value = null)
    {
        if ( $value !== null )
        {
            if ( ! is_string($value) and ! $value instanceof DateTime )
            {
                throw new \InvalidArgumentException("after value must be string or datetime.");
            }

            $this->after = $value;

            return $this;
        }

        return $this->after;
    }


    /**
     * @return array
     */
    public function getAfterValidationRule()
    {
        if ( $this->after !== null )
        {
            if ( $this->after instanceof \DateTime )
            {
                return [ "after" => $this->after->format($this->outputFormat) ];
            }

            return [ "after" => $this->after ];
        }

        return [ ];
    }
}