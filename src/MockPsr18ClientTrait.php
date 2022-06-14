<?php
namespace tomkyle\MockPsr;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Argument;

trait MockPsr18ClientTrait
{
    use ProphecyTrait,
        MockPsr7MessagesTrait;


    public function mockClient(ResponseInterface $response = null) : ClientInterface
    {
        $response = $response ?: $this->mockResponse();

        $client_mock = $this->prophesize(ClientInterface::class);
        $client_mock->sendRequest(Argument::type(RequestInterface::class))->willReturn($response);

        return $client_mock->reveal();
    }
}
