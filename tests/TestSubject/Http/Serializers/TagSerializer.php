<?php namespace TestSubject\Http\Serializers;

use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\ModelSerializer;
use TestSubject\Tag;

class TagSerializer extends ModelSerializer {

    protected $model = Tag::class;


    public function generateFields()
    {
        return [
            "name" => StringField::generate()
        ];
    }
}