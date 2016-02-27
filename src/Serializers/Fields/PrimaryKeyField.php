<?php namespace Kahire\Serializers\Fields;

/**
 * Class PrimaryKeyField
 * @package Kahire\Serializers\Fields
 */
class PrimaryKeyField extends IntegerField {

    /**
     * PrimaryKeyField constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->min(1)->readOnly(true)->required(false);
    }
}