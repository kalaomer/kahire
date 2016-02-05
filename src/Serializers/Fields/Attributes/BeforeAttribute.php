<?php namespace Kahire\Serializers\Fields\Attributes;

use DateTime;

trait BeforeAttribute {

    /**
     * @var string
     */
    protected $before;

    public function before($value)
    {
        if ($value !== null)
        {
            if (! is_string($value) and ! $value instanceof DateTime)
            {
                throw new \InvalidArgumentException("before value must be string or datetime.");
            }

            $this->before = $value;

            return $this;
        }

        return $this->before;
    }

    public function getBeforeValidationRule()
    {
        if ($this->before !== null)
        {
            if ($this->before instanceof DateTime)
            {
                return ["before" => $this->before->format($this->outputFormat)];
            }

            return ["before" => $this->before];
        }

        return [];
    }
}