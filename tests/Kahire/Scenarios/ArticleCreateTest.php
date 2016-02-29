<?php

namespace Kahire\Tests\Scenarios;

use Illuminate\Http\Response;
use Kahire\Serializers\Serializer;
use Kahire\Tests\TestCase;
use Kahire\Tests\UseTestDatabase;
use TestSubject\Http\Serializers\ArticleSerializer;

class ArticleCreateTest extends TestCase
{
    use UseTestDatabase;

    /**
     * @var Serializer
     */
    public $articleSerializer;

    /**
     * @var array
     */
    public $validData;

    /**
     * @var array
     */
    public $validResponse;

    public function setUp()
    {
        parent::setUp();

        $this->articleSerializer = ArticleSerializer::generate();

        $this->validData = [
            'author' => [
                'name' => 'John',
            ],
            'title'  => 'Life with John',
            'tags'   => [
                ['name' => 'life'],
                ['name' => 'john'],
            ],
        ];

        $this->validResponse = [
            'author' => [
                'id'   => 1,
                'name' => 'John',
            ],
            'title'  => 'Life with John',
            'tags'   => [
                ['name' => 'life'],
                ['name' => 'john'],
            ],
        ];
    }

    /**
     * @group develop
     */
    public function testCreate()
    {
        $this->post('article', $this->validData);

        $this->seeJson($this->validResponse);
    }

    public function testUpdate()
    {
        $this->post('article', $this->validData);

        $this->patch('article/1', []);

        $this->assertResponseStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testIndex()
    {
        $this->post('article', $this->validData);

        $this->get('article');
        $this->seeJson([$this->validResponse]);
    }

    public function testShow()
    {
        $this->post('article', $this->validData);

        $this->get('article/1');
        $this->seeJson($this->validResponse);
    }
}
