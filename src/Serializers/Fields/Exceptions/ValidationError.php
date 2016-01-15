<?php namespace Kahire\Serializers\Fields\Exceptions;

class ValidationError extends \Exception {

    public function __construct($message = "", $code = 1, Exception $previous = null)
    {
        if ( is_array($message) )
        {
            $message = implode("; ", $message);
        }

        parent::__construct($message, $code, $previous);
    }
}