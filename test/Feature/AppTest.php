<?php

namespace MedisDemoApp\Tests\Feature;

use MedisDemoApp\Handler\DemoHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class AppTest extends TestCase
{
    /** @test */
    public function it_can_reach_endpoint()
    {
        $handler = new DemoHandler();
        $response = $handler->handle(
            $this->prophesize(ServerRequestInterface::class)->reveal()
        );

        $this->assertEquals($response->getStatusCode(), 200);

        $json = json_decode((string) $response->getBody());
        $this->assertInstanceOf(HtmlResponse::class, $response);
    }
}