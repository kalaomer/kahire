<?php

namespace Kahire\Serializers\Fields\Attributes;

/**
 * Class RequiredAttribute.
 * @method $this required(bool $value)
 */
trait RequiredAttribute
{
    /**
     * @var bool
     */
    protected $required = true;

    protected function getRequiredAttribute()
    {
        return $this->required;
    }

    protected function setRequiredAttribute($value)
    {
        if (! is_bool($value)) {
            throw new \InvalidArgumentException('required must be bool.');
        }

        $this->required = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequiredValidationRule()
    {
        if ($this->required and ! $this->root->partial()) {
            return ['required'];
        }

        return [];
    }
}
