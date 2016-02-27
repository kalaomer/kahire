<?php namespace Kahire\Serializers\Fields\Attributes;

/**
 * Class MimesAttribute
 * @package Kahire\Serializers\Fields\Attributes
 */
trait MimesAttribute {

    /**
     * @var array
     */
    protected $mimes = [ ];


    /**
     * @param array|null $value
     *
     * @return $this|array
     */
    public function mimes(array $value = null)
    {
        if ( $value !== null )
        {
            $this->mimes = $value;

            return $this;
        }

        return $this->mimes;
    }


    /**
     * @return array
     */
    public function getMimesValidationRule()
    {
        return [ "mimes" => $this->mimes ];
    }
}