<?php namespace Kahire\Serializers;

use Illuminate\Database\Eloquent\Model;
use Kahire\Serializers\Fields\DateTimeField;
use Kahire\Serializers\Fields\Field;
use Kahire\Serializers\Fields\PrimaryKeyField;

abstract class ModelSerializer extends Serializer {

    /**
     * @var string
     */
    protected $model;

    /**
     * For auto append PrimaryKey field
     * @var bool
     */
    protected $usePrimaryKey = true;

    /**
     * PrimaryKey field key
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * For auto append created_at and updated_at fields
     * @var bool
     */
    protected $useTimeStamps = true;


    /**
     * @return array
     */
    public function getOneToOneRelations()
    {
        $fields = [ ];

        /* @var $field Field */
        foreach ($this->fields as $field)
        {
            if ( $field instanceof ModelSerializer )
            {
                $fields[] = $field;
            }
        }

        return $fields;
    }


    /**
     * @return array
     */
    public function getOneToManyRelations()
    {
        $fields = [ ];

        foreach ($this->fields as $field)
        {
            if ( $field instanceof ListSerializer && $field->child() instanceof ModelSerializer )
            {
                $fields[] = $field;
            }
        }

        return $fields;
    }


    /**
     * @return array
     */
    public function getBaseFields()
    {
        $fields = [ ];

        foreach ($this->fields as $field)
        {
            if ( ! $field instanceof Serializer )
            {
                $fields[] = $field;
            }
        }

        return $fields;
    }


    public function create($validData)
    {
        $instance = $this->createInstance($validData);

        $this->createOneToOneRelations($validData, $instance);

        $instance->save();

        $this->createOneToManyRelations($validData, $instance);

        return $instance;
    }


    public function createOneToOneRelations($validatedData, Model $instance)
    {
        /* @var $oneToOneRelation ModelSerializer */
        foreach ($this->getOneToOneRelations() as $oneToOneRelation)
        {
            if ( array_key_exists($oneToOneRelation->getFieldName(), $validatedData) )
            {
                $value = $validatedData[$oneToOneRelation->getFieldName()];
                $oneToOneRelation->data($value)->isValid();

                $oneToOneInstance = $oneToOneRelation->save();

                call_user_func([ $instance, $oneToOneRelation->getFieldName() ])->associate($oneToOneInstance);
            }
        }
    }


    public function createOneToManyRelations($validatedData, Model $instance)
    {
        /* @var $oneToManyRelation ListSerializer */
        foreach ($this->getOneToManyRelations() as $oneToManyRelation)
        {
            if ( array_key_exists($oneToManyRelation->getFieldName(), $validatedData) )
            {
                $value    = $validatedData[$oneToManyRelation->getFieldName()];
                $children = $oneToManyRelation->create($value);

                /* @var $child ModelSerializer */
                foreach ($children as $child)
                {
                    call_user_func([ $instance, $oneToManyRelation->getFieldName() ])->save($child);
                };
            }
        }
    }


    /**
     * @param $validatedData
     *
     * @return Model
     */
    public function createInstance($validatedData)
    {
        /* @var $instance Model */
        $instance = new $this->model;

        /* @var $baseField Field */
        foreach ($this->getBaseFields() as $baseField)
        {
            if ( array_key_exists($baseField->getFieldName(), $validatedData) )
            {
                $value                                  = $validatedData[$baseField->getFieldName()];
                $instance->{$baseField->getFieldName()} = $value;
            }
        }

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
        if ( count($this->getOneToOneRelations()) || count($this->getOneToManyRelations()) )
        {
            throw new \BadMethodCallException("This method does not support relation fields. You need to rewrite 'update()' method.");
        }

        foreach ($validatedData as $key => $value)
        {
            $instance->$key = $value;
        }

        $instance->save();

        return $instance;
    }


    protected function generatePrimaryKeyField()
    {
        if ( $this->usePrimaryKey )
        {
            return [
                $this->primaryKey => PrimaryKeyField::generate()
            ];
        }

        return [ ];
    }


    protected function generateTimeStampField()
    {
        if ( $this->useTimeStamps )
        {
            return [
                "created_at" => DateTimeField::generate()->required(false)->readOnly(true),
                "updated_at" => DateTimeField::generate()->required(false)->readOnly(true)
            ];
        }

        return [ ];
    }

}