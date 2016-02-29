<?php

namespace Kahire\Serializers\Fields\Exceptions;

use League\Flysystem\Exception;

/**
 * Class ValidationError.
 */
class ValidationError extends \Exception
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * ValidationError constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($message, $code = 1, Exception $previous = null)
    {
        if (! is_array($message)) {
            $this->errors = [$message];
        } else {
            $this->errors = $message;
        }

        $message = $this->convertMessage($message);

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param $rawMessage
     *
     * @return string
     */
    protected function convertMessage($rawMessage)
    {
        if (is_string($rawMessage)) {
            return $rawMessage;
        }

        $messages = [];

        foreach ($rawMessage as $key => $value) {
            if (is_string($key)) {
                $value = $this->convertMessage($value);
                $messages[] = "$key => $value";
            } else {
                $value = $this->convertMessage($value);
                $messages[] = $value;
            }
        }

        return implode(', ', $messages);
    }

    /**
     * @return array|string
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
