<?php namespace Kahire\Serializers\Fields\Attributes;

trait RequiredAttribute {

    protected $required = true;


    /**
     * @param null $value
     *
     * @return $this|bool
     */
    public function required($value = null)
    {
        if ( $value !== null )
        {
            if ( ! is_bool($value) )
            {
                throw new \InvalidArgumentException("required must be bool.");
            }

            $this->required = $value;

            return $this;
        }

        return $this->required;
    }


    public function getRequiredValidationRule()
    {
        if ( $this->required and ! $this->root->partial() )
        {
            return [ "required" ];
        }

        return [ ];
    }
}