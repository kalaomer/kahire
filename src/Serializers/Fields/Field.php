<?php namespace Kahire\Serializers\Fields;

use Illuminate\Support\Facades\Validator;
use Kahire\Serializers\Fields\Attributes\ReadWriteOnlyAttribute;
use Kahire\Serializers\Fields\Attributes\RequiredAttribute;
use Kahire\Serializers\Fields\DataTypes\EmptyType;
use Kahire\Serializers\Fields\Exceptions\AttributeError;
use Kahire\Serializers\Fields\Exceptions\SkipField;
use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Kahire\Serializers\Serializer;

/**
 * Class Field
 * @package Kahire\Serializers\Fields
 * @method $this allowNull()
 * @method $this allowBlank()
 * @method $this source()
 * @method $this sourceAttr()
 * @method $this default()
 * @method $this validators()
 */
abstract class Field {

    use RequiredAttribute, ReadWriteOnlyAttribute;


    abstract public function toRepresentation($value);


    abstract public function toInternalValue($value);


    protected $allowNull = false;

    protected $allowBlank = false;

    protected $source = null;

    protected $sourceAttr = [ ];

    protected $default;

    protected $validators = [ ];

    protected $validationRules = [ ];

    protected $baseAttributes = [
        "allowNull",
        "allowBlank",
        "source",
        "sourceAttr",
        "default",
        "validators"
    ];

    protected $attributes = [ ];

    /**
     * @var string
     */
    protected $fieldName;

    protected $errorMessages = [
        "required" => "This field is required",
        "invalid"  => "This is not a valid value."
    ];

    /**
     * @var Serializer
     */
    protected $parent;

    /**
     * @var Serializer
     */
    protected $root;


    /**
     * @return static
     */
    public static function generate()
    {
        $reflection = new \ReflectionClass(static::class);

        return $reflection->newInstanceArgs(func_get_args());
    }


    public function __construct()
    {
        array_push($this->attributes, ...$this->baseAttributes);
        $this->default = EmptyType::get();
    }


    public function __call($name, $arguments)
    {
        if ( in_array($name, $this->attributes) )
        {
            if ( isset( $arguments[0] ) )
            {
                $this->$name = $arguments[0];

                return $this;
            }

            return $this->$name;
        }

        throw new \Exception();
    }


    protected function addAttributes(...$attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }


    public function bind($fieldName, $parent)
    {
        $this->fieldName = $fieldName;
        $this->parent    = $parent;

        if ( ! $this->source )
        {
            $this->source = $this->fieldName;
        }

        $root = $this;
        while ($root->parent != null)
        {
            $root = $root->parent;
        }

        $this->root = $root;

        if ( $this->source !== "*" )
        {
            $this->sourceAttr = explode(".", $this->source);
        }
    }


    /**
     * $instance should be an array or object which is implements ArrayAccess interface.
     *
     * @param $instance array|ArrayAccess
     *
     * @return mixed
     * @throws AttributeError
     * @throws SkipField
     */
    public function getAttribute($instance)
    {
        try
        {
            foreach ($this->sourceAttr as $attr)
            {
                $instance = $instance[$attr];
            }

            return $instance;
        }
        catch (\Exception $e)
        {
            // If there is not attribute and not required then throw SkipField
            if ( $this->required == false and EmptyType::isEmpty($this->default) )
            {
                throw new SkipField();
            }

            throw new AttributeError("{$this->fieldName} is not match in {$this->parent->getFieldName()}");
        }
    }


    public function getValue($values)
    {
        if ( ! array_key_exists($this->fieldName, $values) )
        {
            return EmptyType::get();
        }

        return $values[$this->fieldName];
    }


    public function getDefault()
    {
        if ( EmptyType::isEmpty($this->default) )
        {
            throw new SkipField();
        }

        if ( is_callable($this->default) )
        {
            if ( method_exists($this->default, "setContext") )
            {
                $this->default->setContext($this);
            }

            return call_user_func($this->default);
        }

        return $this->default;
    }


    /**
     * @param $data
     *
     * Return [isEmpty, data]
     *
     * @return array
     * @throws SkipField
     * @throws ValidationError
     */
    public function validateEmptyValues($data)
    {
        if ( $this->readOnly )
        {
            return [ true, $this->getDefault() ];
        }

        if ( EmptyType::isEmpty($data) )
        {
            if ( $this->root instanceof Serializer and $this->root->partial() )
            {
                throw new SkipField();
            }

            if ( $this->required )
            {
                $this->fail("required");
            }

            return [ true, $this->getDefault() ];
        }

        if ( $data === null )
        {
            if ( ! $this->allowNull )
            {
                $this->fail("invalid");
            }

            return [ true, null ];
        }

        return [ false, $data ];
    }


    public function runValidationClause($data)
    {
        $validationClause = $this->getValidationClause();

        $validator = Validator::make([
            $this->fieldName => $data
        ], [
            $this->fieldName => $validationClause
        ]);

        if ( $validator->fails() )
        {
            throw new ValidationError($validator->errors()->all());
        }
    }


    public function runValidation($data)
    {
        list( $isEmpty, $data ) = $this->validateEmptyValues($data);

        if ( $isEmpty )
        {
            return $data;
        }

        $value = $this->toInternalValue($data);
        $this->runValidators($value);
        $this->runValidationClause($value);

        return $value;
    }


    public function runValidators($value)
    {
        $errors = [ ];

        foreach ($this->validators as $validator)
        {
            if ( is_object($validator) and method_exists($validator, "setContext") )
            {
                $validator->setContext($this);
            }

            try
            {
                $validator($value);
            }
            catch (ValidationError $e)
            {
                $errors[] = $e->getMessage();
            }
        }

        if ( $errors != [ ] )
        {
            throw new ValidationError($errors);
        }
    }


    public function fail($key, ...$args)
    {
        $message = sprintf($this->errorMessages[$key], ...$args);

        throw new ValidationError($message);
    }


    public function addValidationRules($rules)
    {
        $this->validationRules = array_merge($this->validationRules, $rules);

        return $this;
    }


    /**
     * Get custom validation rules from field.
     * @return array
     */
    public function getValidationRules()
    {
        $rules = [ ];

        foreach (get_class_methods(get_called_class()) as $method)
        {
            if ( preg_match("/^get[A-Za-z]+ValidationRule$/", $method) )
            {
                $rules = array_merge($rules, call_user_func([ $this, $method ]));
            }
        }

        return $rules;
    }


    public function getValidationClause()
    {
        $validationRules = array_merge($this->validationRules, $this->getValidationRules());

        $ruleClauses = [ ];

        foreach ($validationRules as $ruleKey => $ruleValue)
        {
            // If ruleKey is string, then it will validation name
            if ( is_string($ruleKey) )
            {
                $ruleClause = $ruleKey . ":";

                if ( is_array($ruleValue) )
                {
                    $ruleClause = implode(",", $ruleValue);
                }
                else
                {
                    $ruleClause .= $ruleValue;
                }
            }
            else
            {
                $ruleClause = $ruleValue;
            }

            $ruleClauses[] = $ruleClause;
        }

        return implode("|", $ruleClauses);
    }


    public function getFieldName()
    {
        return $this->fieldName;
    }
}