<?php

namespace Kahire\Serializers\Fields\Attributes;

/**
 * Class MinimumAttribute.
 * @method $this min(int $value)
 */
trait MinimumAttribute
{
    /**
     * @var null
     */
    protected $min = null;

    protected function getMinAttribute()
    {
        return $this->min;
    }

    protected function setMinAttribute($value)
    {
        if (! is_integer($value)) {
            throw new \InvalidArgumentException('value must be integer.');
        }

        $this->min = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getMinimumValidationRule()
    {
        if ($this->min !== null) {
            return ['min' => $this->min];
        }

        return [];
    }
}
