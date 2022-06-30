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

        $client_mock = (new Prophecy\Prophet)->prophesize(ClientInterface::class);
        $client_mock->sendRequest(Prophecy\Argument::type(RequestInterface::class))->willReturn($response);

        return $client_mock->reveal();
    }
}
