<?php namespace Kahire\Tests\Middleware;

use Illuminate\Http\Response;
use Kahire\Tests\TestCase;

class ValidationMiddlewareTest extends TestCase {

    public function test404Request()
    {
        $invalidData = [
            "integer" => "string"
        ];

        $this->post("foo", $invalidData);
        $this->assertResponseStatus(Response::HTTP_BAD_REQUEST);
    }
}