<?php namespace Kahire\Serializers\Fields\Attributes;

trait MinimumAttribute {

    protected $min = null;


    /**
     * @param integer $value
     *
     * @return $this
     */
    public function min($value = null)
    {
        if ( $value !== null )
        {
            if ( ! is_integer($value) )
            {
                throw new \InvalidArgumentException("value must be integer.");
            }

            $this->min = $value;

            return $this;
        }

        return $this->min;
    }


    public function getMinimumValidationRule()
    {
        if ( $this->min !== null )
        {
            return [ "min" => $this->min ];
        }

        return [ ];
    }
}