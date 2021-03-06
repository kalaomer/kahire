<?php

namespace Kahire\Serializers\Fields;

/**
 * Class EmailField.
 */
class EmailField extends StringField
{
    /**
     * EmailField constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addValidationRules(['email']);
    }
}
