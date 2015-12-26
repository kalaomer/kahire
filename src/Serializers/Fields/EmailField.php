<?php namespace Kahire\Serializers\Fields;

class EmailField extends StringField {

    public function __construct()
    {
        parent::__construct();

        $this->addValidationRules([ "email" ]);
    }
}