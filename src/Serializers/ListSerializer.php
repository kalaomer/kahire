<?php namespace Kahire\Serializers;

use DeepCopy\DeepCopy;
use Kahire\Serializers\Fields\Exceptions\ValidationError;

/**
 * Class ListSerializer
 * @method $this allowEmpty()
 * @package Kahire\Serializers
 */
class ListSerializer extends Serializer {

    protected $many = true;

    protected $allowEmpty = true;

    protected $attributes = [ "allowEmpty" ];

    /**
     * @var Serializer
     */
    protected $child;


    /**
     * @param Serializer|null $child
     *
     * @return $this|Serializer
     */
    public function child(Serializer $child = null)
    {
        if ( $child )
        {
            $deepCopy    = new DeepCopy();
            $this->child = $deepCopy->copy($child);
            $this->child->bind("", $this);

            return $this;
        }

        return $this->child;
    }


    public function getFields()
    {
        return $this->fields;
    }


    public function create($validatedData)
    {
        $instances = [ ];

        foreach ($validatedData as $item)
        {
            $instances[] = $this->child->create($item);
        }

        return $instances;
    }


    public function update($instance, $validatedData)
    {
        throw new \BadMethodCallException("Serializers with many=True do not support multiple update by " . "default, only multiple create. For updates it is unclear how to " . "deal with insertions and deletions. If you need to support " . "multiple update, use a `ListSerializer` class and override " . "`.update()` so you can specify the behavior exactly.");
    }


    public function getInitial()
    {
        if ( $this->initialData )
        {
            return $this->toRepresentation($this->initialData);
        }

        return [ ];
    }


    /**
     * @param $data
     *
     * @return array
     * @throws ValidationError
     */
    public function toInternalValue($data)
    {
        if ( ! $this->allowEmpty and count($data) == 0 )
        {
            $this->fail("invalid");
        }

        $values = [ ];
        $errors = [ ];

        foreach ($data as $item)
        {
            try
            {
                $validated = $this->child->runValidation($item);
            }
            catch (ValidationError $e)
            {
                $errors[] = $e->getErrors();
                continue;
            }

            $values[] = $validated;
        }

        if ( $errors !== [ ] )
        {
            throw new ValidationError($errors);
        }

        return $values;
    }


    public function toRepresentation($data)
    {
        $values = [ ];

        foreach ($data as $item)
        {
            $values[] = $this->child->toRepresentation($item);
        }

        return $values;
    }
}