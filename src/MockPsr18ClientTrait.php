<?php

namespace tomkyle\MockPsr;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Prophecy;

trait MockPsr18ClientTrait
{
    use MockPsr7MessagesTrait;

    public function mockClient(ResponseInterface $response = null): ClientInterface
    {
        $response = $response ?: $this->mockResponse();

        $objectProphecy = (new Prophecy\Prophet)->prophesize(ClientInterface::class);
        $objectProphecy->sendRequest(Prophecy\Argument::type(RequestInterface::class))->willReturn($response);

        return $objectProphecy->reveal();
    }
}
