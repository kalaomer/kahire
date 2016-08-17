<?php

namespace Kahire\Serializers\Fields;

/**
 * Class BooleanField.
 */
class BooleanField extends Field
{
    /**
     *
     */
    const TRUE_VALUES = ['t', 'T', 'true', 'True', 'TRUE', '1', 1, true];
    /**
     *
     */
    const FALSE_VALUES = ['f', 'F', 'false', 'False', 'FALSE', '0', 0, false];

    /**
     * @param $value
     *
     * @return bool
     */
    public function toRepresentation($value)
    {
        // Can not use is_array or array_search
        // Because they always return TRUE because of that `true` value which is in TRUE_VALUES.
        foreach (self::TRUE_VALUES as $trueValue) {
            if ($trueValue === $value) {
                return true;
            }
        }

        foreach (self::FALSE_VALUES as $falseValue) {
            if ($falseValue === $value) {
                return false;
            }
        }

        return (bool) $value;
    }

    /**
     * @param $value
     *
     * @return bool
     * @throws Exceptions\ValidationError
     */
    public function toInternalValue($value)
    {
        foreach (self::TRUE_VALUES as $trueValue) {
            if ($trueValue === $value) {
                return true;
            }
        }

        foreach (self::FALSE_VALUES as $falseValue) {
            if ($falseValue === $value) {
                return false;
            }
        }

        $this->fail('invalid');
    }
}
