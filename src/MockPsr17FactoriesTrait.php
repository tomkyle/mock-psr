<?php

namespace tomkyle\MockPsr;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Prophecy;

trait MockPsr17FactoriesTrait
{
    use MockPsr7MessagesTrait;


    public function mockRequestFactory(?RequestInterface $request = null): RequestFactoryInterface
    {
        $request = $request ?: $this->mockRequest("GET", "/");
        $objectProphecy = (new Prophecy\Prophet)->prophesize(RequestFactoryInterface::class);
        $objectProphecy->createRequest(Prophecy\Argument::type('string'), Prophecy\Argument::any())->willReturn($request);

        return $objectProphecy->reveal();
    }

    public function mockResponseFactory(?ResponseInterface $response = null): ResponseFactoryInterface
    {
        $response = $response ?: $this->mockResponse();
        $objectProphecy = (new Prophecy\Prophet)->prophesize(ResponseFactoryInterface::class);
        $objectProphecy->createResponse()->willReturn($response);
        $objectProphecy->createResponse(Prophecy\Argument::type('int'), Prophecy\Argument::any())->willReturn($response);

        return $objectProphecy->reveal();
    }
}
