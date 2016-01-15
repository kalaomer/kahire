<?php namespace TestSubject\Http\Controllers;

use Kahire\ViewSets\ModelViewSet;
use TestSubject\Foo;
use TestSubject\Http\Serializers\FooSerializer;

class FooController extends ModelViewSet {

    public $model = Foo::class;

    public $serializer = FooSerializer::class;
}