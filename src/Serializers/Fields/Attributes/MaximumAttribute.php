<?php namespace Kahire\Serializers\Fields\Attributes;

trait MaximumAttribute {

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


    public function getMaximumValidationRule()
    {
        if ( $this->max !== null )
        {
            return [ "max" => $this->max ];
        }

        return [ ];
    }
}