<?php namespace Kahire\tests\Kahire\Serializers\Fields;

use Kahire\Serializers\Fields\BooleanField;

class BooleanFieldTest extends FieldTestCase {

    public $fieldClass = BooleanField::class;

    /**
     * @var BooleanField
     */
    public $field;

    public $validInputs = [
        "t"    => true,
        "T"    => true,
        "true" => true,
        "True" => true,
        "TRUE" => true,
        "1"    => true,
        1      => true,
        true   => true,

        "f"     => false,
        "F"     => false,
        "false" => false,
        "False" => false,
        "FALSE" => false,
        "0"     => false,
        0       => false,
        false   => false
    ];

    public $invalidInputs = [
        "foo",
        "hey!",
        2,
        3.3
    ];

    public $outputs = [
        "1" => true
    ];
}
