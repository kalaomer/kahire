<?php namespace Kahire\Serializers\Fields\Attributes;

trait ReadWriteOnlyAttribute {

    /**
     * @var bool
     */
    protected $readOnly = false;

    /**
     * @var bool
     */
    protected $writeOnly = false;


    /**
     * @param null $value
     *
     * @return $this|bool
     */
    public function readOnly($value = null)
    {
        if ( $value !== null )
        {
            if ( ! is_bool($value) )
            {
                throw new \InvalidArgumentException("readOnly must be bool.");
            }

            if ( $value and $this->writeOnly )
            {
                throw new \InvalidArgumentException("readOnly can't be `true` when writeOnly is `true`");
            }

            $this->readOnly = $value;

            return $this;
        }

        return $this->readOnly;
    }


    /**
     * @param null $value
     *
     * @return $this|bool
     */
    public function writeOnly($value = null)
    {
        if ( $value !== null )
        {
            if ( ! is_bool($value) )
            {
                throw new \InvalidArgumentException("writeOnly must be bool.");
            }

            if ( $value and $this->readOnly )
            {
                throw new \InvalidArgumentException("writeOnly can't be `true` when readOnly is `true`");
            }

            $this->writeOnly = $value;

            return $this;
        }

        return $this->writeOnly;
    }

}