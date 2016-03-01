<?php

namespace Kahire\Serializers\Fields\Attributes;

use DateTime;
use InvalidArgumentException;

/**
 * Class AfterAttribute.
 * @method $this after(string $value)
 */
trait AfterAttribute
{
    /**
     * @var string|DateTime
     */
    protected $after;

    protected function getAfterAttribute()
    {
        return $this->after;
    }

    protected function setAfterAttribute($value)
    {
        if (! is_string($value) and ! $value instanceof DateTime) {
            throw new InvalidArgumentException('after value must be string or datetime.');
        }

        $this->after = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getAfterValidationRule()
    {
        if ($this->after !== null) {
            if ($this->after instanceof \DateTime) {
                return ['after' => $this->after->format($this->outputFormat)];
            }

            return ['after' => $this->after];
        }

        return [];
    }
}
