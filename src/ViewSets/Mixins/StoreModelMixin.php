<?php namespace Kahire\ViewSets\Mixins;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kahire\Serializers\Serializer;

trait StoreModelMixin {

    /**
     * @return Serializer
     */
    abstract public function getSerializer();


    /**
     * @param Request $request
     *
     * @return array|JsonResponse
     * @throws \AssertionError
     * @throws \Kahire\Serializers\Fields\Exceptions\ValidationError
     */
    public function store(Request $request)
    {
        $serializer = $this->getSerializer();
        $serializer->data($request->all());
        $serializer->isValid(true);

        $this->performStore($serializer);

        return $request->all();

        return new JsonResponse($serializer->data(), JsonResponse::HTTP_CREATED);
    }


    /**
     * @param Serializer $serializer
     */
    public function performStore(Serializer $serializer)
    {
        $serializer->save();
    }
}