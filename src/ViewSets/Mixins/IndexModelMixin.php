<?php namespace Kahire\ViewSets\Mixins;

use Illuminate\Database\Eloquent\Builder;
use Kahire\Serializers\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

trait IndexModelMixin {

    /**
     * @return Builder
     */
    abstract public function getQuerySet();


    /**
     * @return Serializer;
     */
    abstract public function getSerializer();


    /**
     * @return JsonResponse
     * @throws \AssertionError
     */
    public function index()
    {
        $querySet = $this->getQuerySet();

        $serializer = $this->getSerializer()->many()->instance($querySet->get());

        return new JsonResponse($serializer->data(), JsonResponse::HTTP_OK);
    }
}