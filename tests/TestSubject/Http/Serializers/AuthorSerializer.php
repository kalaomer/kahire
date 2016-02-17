<?php namespace TestSubject\Http\Serializers;

use Kahire\Serializers\Fields\PrimaryKeyField;
use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\ModelSerializer;
use TestSubject\Author;

class AuthorSerializer extends ModelSerializer {

    protected $model = Author::class;


    public function generateFields()
    {
        return [
            "id"   => PrimaryKeyField::generate(),
            "name" => StringField::generate()
        ];
    }
}