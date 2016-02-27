<?php namespace TestSubject\Http\Serializers;

use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\ModelSerializer;
use TestSubject\Author;

class AuthorSerializer extends ModelSerializer {

    protected $model = Author::class;

    protected $useTimeStamps = false;


    public function generateFields()
    {
        return [
            "name" => StringField::generate()
        ];
    }
}