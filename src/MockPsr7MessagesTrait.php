<?php
namespace tomkyle\MockPsr;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

use Nyholm;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

trait MockPsr7MessagesTrait
{
    public function mockServerRequest(array $attributes = array(), array $headers = array()) : ServerRequestInterface
    {
        $request_mock = $this->prophesize(ServerRequestInterface::class);

        foreach ($attributes as $name => $value) {
            $request_mock->getAttribute(Argument::exact($name))->willReturn($value);
        }
        foreach ($headers as $name => $value) {
            $request_mock->getHeaderLine(Argument::exact($name))->willReturn($value);
        }
        return $request_mock->reveal();
    }


    public function mockUri($uri) : UriInterface
    {
        return is_string($uri)
        ? (new Nyholm\Psr7\Factory\Psr17Factory)->createUri($uri)
        : $uri;
    }


    public function mockRequest(string $method, $uri) : RequestInterface
    {
        $uri = $this->mockUri($uri);

        $request_mock = $this->prophesize(RequestInterface::class);
        $request_mock->getMethod()->willReturn($method);
        $request_mock->getUri()->willReturn($uri);

        return $request_mock->reveal();
    }


    public function mockStream(string $body = '', array $options = array()) : StreamInterface
    {
        $stream_mock = $this->prophesize(StreamInterface::class);
        $stream_mock->__toString()->willReturn($body);

        if ($options['write'] ?? false):
            $stream_mock->write(Argument::type('string'))->shouldBeCalled();
        endif;

        return $stream_mock->reveal();
    }


    public function mockResponse(int $status = 200, $body = null) : ResponseInterface
    {
        $response_mock = $this->prophesize(ResponseInterface::class);
        $response_mock->getStatusCode()->willReturn($status);

        if ($body instanceof StreamInterface) {
            $response_mock->getBody()->willReturn($body);
        } elseif (is_string($body)) {
            $stream = $this->mockStream($body);
            $response_mock->getBody()->willReturn($stream);
        }

        return $response_mock->reveal();
    }
}
