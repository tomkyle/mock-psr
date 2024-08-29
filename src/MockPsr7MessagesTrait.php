<?php

namespace tomkyle\MockPsr;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

use Nyholm;
use Prophecy;

trait MockPsr7MessagesTrait
{
    public function mockServerRequest(array $attributes = [], array $headers = []): ServerRequestInterface
    {
        $objectProphecy = (new Prophecy\Prophet)->prophesize(ServerRequestInterface::class);

        foreach ($attributes as $name => $value) {
            $objectProphecy->getAttribute(Prophecy\Argument::exact($name))->willReturn($value);
        }

        foreach ($headers as $name => $value) {
            $objectProphecy->getHeaderLine(Prophecy\Argument::exact($name))->willReturn($value);
        }

        return $objectProphecy->reveal();
    }


    public function mockUri($uri): UriInterface
    {
        return is_string($uri)
        ? (new Nyholm\Psr7\Factory\Psr17Factory())->createUri($uri)
        : $uri;
    }


    public function mockRequest(string $method, $uri): RequestInterface
    {
        $uri = $this->mockUri($uri);

        $objectProphecy = (new Prophecy\Prophet)->prophesize(RequestInterface::class);
        $objectProphecy->getMethod()->willReturn($method);
        $objectProphecy->getUri()->willReturn($uri);

        return $objectProphecy->reveal();
    }


    public function mockStream(string $body = '', array $options = []): StreamInterface
    {
        $objectProphecy = (new Prophecy\Prophet)->prophesize(StreamInterface::class);
        $objectProphecy->__toString()->willReturn($body);

        if ($options['write'] ?? false):
            $objectProphecy->write(Prophecy\Argument::type('string'))->shouldBeCalled();
        endif;

        return $objectProphecy->reveal();
    }


    public function mockResponse(int $status = 200, $body = null): ResponseInterface
    {
        $objectProphecy = (new Prophecy\Prophet)->prophesize(ResponseInterface::class);
        $objectProphecy->getStatusCode()->willReturn($status);

        if ($body instanceof StreamInterface) {
            $objectProphecy->getBody()->willReturn($body);
        } elseif (is_string($body)) {
            $stream = $this->mockStream($body);
            $objectProphecy->getBody()->willReturn($stream);
        }

        return $objectProphecy->reveal();
    }
}
