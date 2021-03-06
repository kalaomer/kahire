<?php

namespace TestSubject\Http\Serializers;

use Kahire\Serializers\Fields\IntegerField;
use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\ModelSerializer;
use TestSubject\Basic;

class BasicSerializer extends ModelSerializer
{
    protected $model = Basic::class;

    protected $usePrimaryKey = false;

    protected $useTimeStamps = false;

    public function generateFields()
    {
        return [
            'string'  => StringField::generate(),
            'integer' => IntegerField::generate(),
        ];
    }
}
