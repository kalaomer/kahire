<?php

namespace Kahire\Serializers\Fields\Attributes;

use DateTime;

/**
 * Class BeforeAttribute.
 * @method $this before(string $value)
 */
trait BeforeAttribute
{
    /**
     * @var string|DateTime
     */
    protected $before;

    protected function getBeforeAttribute()
    {
        return $this->before;
    }

    protected function setBeforeAttribute($value)
    {
        if (! is_string($value) and ! $value instanceof DateTime) {
            throw new \InvalidArgumentException('before value must be string or datetime.');
        }

        $this->before = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getBeforeValidationRule()
    {
        if ($this->before !== null) {
            if ($this->before instanceof DateTime) {
                return ['before' => $this->before->format($this->outputFormat)];
            }

            return ['before' => $this->before];
        }

        return [];
    }
}
