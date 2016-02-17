<?php namespace Kahire\Serializers\Fields\Attributes;

use DateTime;

trait AfterAttribute {

    /**
     * @var string
     */
    protected $after;


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