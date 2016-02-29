<?php

namespace TestSubject;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = true;

    protected $table = 'tags';
}
