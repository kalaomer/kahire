<?php

namespace Kahire\ViewSets;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Kahire\Serializers\Serializer;

abstract class GenericViewSet extends Controller
{
    /**
     * Model Class name.
     * @var string
     */
    public $model;

    /**
     * Serializer Class name.
     * @var string
     */
    public $serializer;

    /**
     * @var string
     */
    public $lookupField = 'id';

    /**
     * @return Builder
     */
    public function getQuerySet()
    {
        /* @var $instance Model */
        $instance = new $this->model;

        return $instance->newQuery();
    }

    /**
     * @return Model
     */
    public function getObject()
    {
        // Get ID from url segments.
        $segments = Request::segments();
        $id = end($segments);

        return $this->getQuerySet()->where($this->lookupField, $id)->firstOrFail();
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        $serializerClass = $this->getSerializerClass();
        /* @var $serializer Serializer */
        $serializer = new $serializerClass;
        $serializer->context($this->getSerializerContext());

        return $serializer;
    }

    public function getSerializerClass()
    {
        return $this->serializer;
    }

    public function getSerializerContext()
    {
        return [
            'view' => $this,
        ];
    }
}
