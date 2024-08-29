<?php

namespace tomkyle\MockPsr;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Prophecy;

trait MockPsr15RequestHandlerTrait
{
    use MockPsr7MessagesTrait;

    public function mockRequestHandler($response = null): RequestHandlerInterface
    {
        $response = $response ?: $this->mockResponse();
        $objectProphecy = (new Prophecy\Prophet)->prophesize(RequestHandlerInterface::class);

        if ($response instanceof ResponseInterface) {
            $objectProphecy->handle(Prophecy\Argument::type(ServerRequestInterface::class))->willReturn($response);
        } elseif ($response instanceof \Throwable) {
            $objectProphecy->handle(Prophecy\Argument::type(ServerRequestInterface::class))->willThrow($response);
        }

        return $objectProphecy->reveal();
    }
}
