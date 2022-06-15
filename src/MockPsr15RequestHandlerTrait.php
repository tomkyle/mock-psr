<?php

namespace tomkyle\MockPsr;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

trait MockPsr15RequestHandlerTrait
{
    use MockPsr7MessagesTrait;
    use ProphecyTrait;

    public function mockRequestHandler($response = null): RequestHandlerInterface
    {
        $response = $response ?: $this->mockResponse();

        $handler_mock = $this->prophesize(RequestHandlerInterface::class);

        if ($response instanceof ResponseInterface) {
            $handler_mock->handle(Argument::type(ServerRequestInterface::class))->willReturn($response);
        } elseif ($response instanceof \Throwable) {
            $handler_mock->handle(Argument::type(ServerRequestInterface::class))->willThrow($response);
        }

        return $handler_mock->reveal();
    }
}
