<?php namespace Kahire\Serializers\Fields;

class PrimaryKeyField extends IntegerField {

    public function __construct()
    {
        parent::__construct();

        $this->min(1)->readOnly(true)->required(false);
    }
}