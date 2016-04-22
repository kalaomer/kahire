<?php

namespace Kahire\Commands\Parsers;

class FieldParser {

    /**
     * @var string
     */
    protected $fieldString;

    public function __construct($fieldString)
    {
        $this->fieldString = $fieldString;
    }
}