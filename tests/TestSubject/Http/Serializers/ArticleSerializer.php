<?php

namespace TestSubject\Http\Serializers;

use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\ModelSerializer;
use TestSubject\Article;

class ArticleSerializer extends ModelSerializer
{
    protected $model = Article::class;

    protected $usePrimaryKey = false;

    protected $useTimeStamps = false;

    public function generateFields()
    {
        return [
            'title'  => StringField::generate(),
            'author' => AuthorSerializer::generate(),
            'tags'   => TagSerializer::generate()->many(),
        ];
    }
}
