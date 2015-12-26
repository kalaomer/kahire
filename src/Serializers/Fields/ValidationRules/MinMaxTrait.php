<?php namespace Kahire\Serializers\Fields\ValidationRules;

trait MinMaxTrait {

    protected $min = null;

    protected $max = null;


    protected function getMinMaxValidationRules()
    {
        $validationRules = [ ];

        if ( $this->min )
        {
            $validationRules["min"] = $this->min;
        }

        if ( $this->max )
        {
            $validationRules["max"] = $this->max;
        }

        return $validationRules;
    }
}