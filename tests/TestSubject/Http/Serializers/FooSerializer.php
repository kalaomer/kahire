<?php namespace TestSubject\Http\Serializers;

use Kahire\Serializers\Fields\IntegerField;
use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\ModelSerializer;
use TestSubject\Foo;

class FooSerializer extends ModelSerializer {

    protected $model = Foo::class;


    public function getFields()
    {
        return [
            "string"  => StringField::generate(),
            "integer" => IntegerField::generate()
        ];
    }
}