<?php namespace Kahire\Serializers;

use Illuminate\Database\Eloquent\Model;

abstract class ModelSerializer extends Serializer {

    /**
     * @var string
     */
    protected $model;


    public function create($validatedData)
    {
        /* @var $instance Model */
        $instance = new $this->model;
        foreach ($validatedData as $key => $value)
        {
            $instance->$key = $value;
        }

        $instance->save();

        return $instance;
    }


    /**
     * @param $instance Model
     * @param $validatedData
     *
     * @return mixed
     */
    public function update($instance, $validatedData)
    {
        foreach ($validatedData as $key => $value)
        {
            $instance->$key = $value;
        }

        $instance->save();

        return $instance;
    }

}