<?php namespace Kahire\Serializers\Fields\DataTypes;

/**
 * Class EmptyType
 *
 * This class is used to represent no data being provided for a given input
 * or output value.
 *
 * It is required because `Null` may be a valid input or output value.
 *
 * @package Kahire\Serializers\Fields\DataTypes
 */
class EmptyType {

    public static $instance = null;


    public static function get()
    {
        if ( self::$instance === null )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public static function isEmpty($value)
    {
        return $value === self::$instance;
    }
}