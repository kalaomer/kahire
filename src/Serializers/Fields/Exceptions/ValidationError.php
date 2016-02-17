<?php namespace Kahire\Serializers\Fields\Exceptions;

use League\Flysystem\Exception;

class ValidationError extends \Exception {

    /**
     * @var array
     */
    protected $errors = [ ];


    public function __construct($message, $code = 1, Exception $previous = null)
    {
        if ( ! is_array($message) )
        {
            $this->errors = [ $message ];
        }
        else
        {
            $this->errors = $message;
        }

        $message = $this->convertMessage($message);

        parent::__construct($message, $code, $previous);
    }


    protected function convertMessage($rawMessage)
    {
        if ( is_string($rawMessage) )
        {
            return $rawMessage;
        }

        $messages = [ ];

        foreach ($rawMessage as $key => $value)
        {
            if ( is_string($key) )
            {
                $value      = $this->convertMessage($value);
                $messages[] = "$key => $value";
            }
            else
            {
                $value      = $this->convertMessage($value);
                $messages[] = $value;
            }
        }

        return implode(", ", $messages);
    }


    public function getErrors()
    {
        return $this->errors;
    }
}