<?php

namespace Kahire\Serializers\Fields\Attributes;

/**
 * Class MimesAttribute.
 * @method $this mimes($value)
 */
trait MimesAttribute
{
    /**
     * @var array
     */
    protected $mimes = [];

    protected function getMimesAttribute()
    {
        return $this->mimes;
    }

    protected function setMimesAttribute(array $value)
    {
        $this->mimes = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getMimesValidationRule()
    {
        return ['mimes' => $this->mimes];
    }
}
