<?php

namespace Kahire\ViewSets\Mixins;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Kahire\Serializers\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

trait UpdateModelMixin
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
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \AssertionError
     * @throws \Kahire\Serializers\Fields\Exceptions\ValidationError
     */
    public function update(Request $request)
    {
        $instance = $this->getObject();
        $serializer = $this->getSerializer();

        $serializer->instance($instance)->partial(true)->data($request->all())->isValid(true);

        $serializer->save();

        return new JsonResponse($serializer->data(), JsonResponse::HTTP_OK);
    }

    /**
     * @param Serializer $serializer
     */
    public function performUpdate(Serializer $serializer)
    {
        $serializer->save();
    }
}
