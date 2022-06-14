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

use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Argument;

trait MockPsr17FactoriesTrait
{
    use ProphecyTrait;
    use MockPsr7MessagesTrait;


    public function mockRequestFactory(RequestInterface $request = null): RequestFactoryInterface
    {
        $request = $request ?: $this->mockRequest("GET", "/");

        $factory_mock = $this->prophesize(RequestFactoryInterface::class);
        $factory_mock->createRequest(Argument::type('string'), Argument::any())->willReturn($request);

        return $factory_mock->reveal();
    }

    public function mockResponseFactory(ResponseInterface $response = null): ResponseFactoryInterface
    {
        $response = $response ?: $this->mockResponse();

        $factory_mock = $this->prophesize(ResponseFactoryInterface::class);
        $factory_mock->createResponse()->willReturn($response);
        $factory_mock->createResponse(Argument::type('int'), Argument::any())->willReturn($response);

        return $factory_mock->reveal();
    }
}
