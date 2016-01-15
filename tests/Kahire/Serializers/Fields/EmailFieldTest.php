<?php namespace Kahire\Tests\Serializers\Fields;

use Kahire\Serializers\Fields\EmailField;

class EmailFieldTest extends FieldTestCase {

    public $fieldClass = EmailField::class;

    /**
     * @var EmailField
     */
    public $field;

    public $validInputs = [
        "foo@bar.com" => "foo@bar.com"
    ];

    public $invalidInputs = [
        "foo",
        "hey!"
    ];

    public $outputs = [
        "foo@bar.com" => "foo@bar.com"
    ];
}
