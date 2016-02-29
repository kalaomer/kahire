<?php

namespace Kahire\Serializers\Fields\Attributes;

/**
 * Class MaximumAttribute.
 * @method $this max(int $value)
 */
trait MaximumAttribute
{
    /**
     * @var null
     */
    protected $max = null;

    protected function getMaxAttribute()
    {
        return $this->max;
    }

    protected function setMaxAttribute($value)
    {
        if (! is_integer($value)) {
            throw new \InvalidArgumentException('value must be integer.');
        }

        $this->max = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getMaximumValidationRule()
    {
        if ($this->max !== null) {
            return ['max' => $this->max];
        }

        return [];
    }
}
