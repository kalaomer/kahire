<?php namespace Kahire\Serializers\Fields\Attributes;

trait MimesAttribute {

    protected $mimes = [ ];


    public function mimes(array $value = null)
    {
        if ( $value !== null )
        {
            $this->mimes = $value;

            return $this;
        }

        return $this->mimes;
    }


    public function getMimesValidationRule()
    {
        return [ "mimes" => $this->mimes ];
    }
}