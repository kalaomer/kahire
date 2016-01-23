<?php namespace Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\Attributes\MaximumAttribute;
use Kahire\Serializers\Fields\Attributes\MimesAttribute;
use Symfony\Component\HttpFoundation\File\File;

class FileField extends Field {

    use MaximumAttribute, MimesAttribute;


    /**
     * @param $value
     *
     * @return File
     * @throws Exceptions\ValidationError
     */
    public function toInternalValue($value)
    {
        if ( ! $value instanceof File )
        {
            $this->fail("invalid");
        }

        return $value;
    }


    /**
     * @param $value
     *
     * @return mixed
     */
    public function toRepresentation($value)
    {
        return $value->getRealPath();
    }
}