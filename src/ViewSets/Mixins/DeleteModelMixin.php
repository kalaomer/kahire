<?php namespace Kahire\ViewSets\Mixins;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\JsonResponse;

trait DeleteModelMixin {

    /**
     * @return Model
     */
    abstract public function getObject();


    /**
     * @param ...$ids
     *
     * @return JsonResponse
     */
    public function delete()
    {
        $instance = $this->getObject();
        $this->performDelete($instance);

        return new JsonResponse([ ], JsonResponse::HTTP_NO_CONTENT);
    }


    /**
     * @param Model $instance
     *
     * @throws \Exception
     */
    public function performDelete(Model $instance)
    {
        $instance->delete();
    }
}