<?php namespace TestSubject;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

    public $timestamps = true;

    protected $table = "articles";


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Author::class, "author_id");
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, "article_tag", "article_id", "tag_id");
    }
}