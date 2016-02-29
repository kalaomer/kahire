<?php

namespace Kahire\ViewSets\Mixins;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Kahire\Serializers\Serializer;

trait ShowModelMixin
{
    /**
     * @return Serializer
     */
    abstract public function getSerializer();

    /**
     * @return Model
     */
    abstract public function getObject();

    /**
     * @return JsonResponse
     * @throws \AssertionError
     */
    public function show()
    {
        $instance = $this->getObject();

        $serializer = $this->getSerializer()->instance($instance);

        return new JsonResponse($serializer->data(), JsonResponse::HTTP_OK);
    }
}
