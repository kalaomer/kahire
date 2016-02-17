<?php namespace TestSubject\Http\Controllers;

use Kahire\ViewSets\ModelViewSet;
use TestSubject\Basic;
use TestSubject\Http\Serializers\BasicSerializer;

class BasicController extends ModelViewSet {

    public $model = Basic::class;

    public $serializer = BasicSerializer::class;
}