<?php

namespace TestSubject\Http\Controllers;

use Kahire\ViewSets\ModelViewSet;
use TestSubject\Article;
use TestSubject\Http\Serializers\ArticleSerializer;

class ArticleController extends ModelViewSet
{
    public $serializer = ArticleSerializer::class;

    public $model = Article::class;
}
