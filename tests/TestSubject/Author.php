<?php namespace TestSubject;

use Illuminate\Database\Eloquent\Model;

class Author extends Model {

    public $timestamps = true;

    protected $table = "authors";
}