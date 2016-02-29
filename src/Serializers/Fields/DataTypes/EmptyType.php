<?php

namespace Kahire\Serializers\Fields\DataTypes;

/**
 * Class EmptyType.
 *
 * This class is used to represent no data being provided for a given input
 * or output value.
 *
 * It is required because `Null` may be a valid input or output value.
 */
/**
 * Class EmptyType.
 */
class EmptyType
{
    /**
     * @var EmptyType
     */
    public static $instance = null;

    /**
     * @return EmptyType
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public static function isEmpty($value)
    {
        return $value === self::$instance;
    }
}
