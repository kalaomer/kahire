<?php

namespace Kahire\Serializers\Fields\Attributes;

/**
 * Class ReadWriteOnlyAttribute.
 * @method $this readOnly(bool $value)
 * @method $this writeOnly(bool $value)
 */
trait ReadWriteOnlyAttribute
{
    /**
     * @var bool
     */
    protected $readOnly = false;

    /**
     * @var bool
     */
    protected $writeOnly = false;

    protected function getReadOnlyAttribute()
    {
        return $this->readOnly;
    }

    protected function setReadOnlyAttribute($value)
    {
        if (! is_bool($value)) {
            throw new \InvalidArgumentException('readOnly must be bool.');
        }

        if ($value and $this->writeOnly) {
            throw new \InvalidArgumentException("readOnly can't be `true` when writeOnly is `true`");
        }

        $this->readOnly = $value;

        return $this;
    }

    protected function getWriteOnlyAttribute()
    {
        return $this->writeOnly;
    }

    protected function setWriteOnlyAttribute($value)
    {
        if (! is_bool($value)) {
            throw new \InvalidArgumentException('writeOnly must be bool.');
        }

        if ($value and $this->readOnly) {
            throw new \InvalidArgumentException("writeOnly can't be `true` when readOnly is `true`");
        }

        $this->writeOnly = $value;

        return $this;
    }
}
