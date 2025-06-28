<?php

/**
 * This file is part of tomkyle/mock-psr
 *
 * Traits for mocking common PSR components in PhpUnit tests
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tomkyle\MockPsr;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

trait MockPsr7MessagesTrait
{
    /**
     * Create a mock PSR-7 RequestInterface.
     *
     * Sets getMethod() and getUri() behavior.
     *
     * Usage:
     *
     * <code>
     * $request = $this->mockRequest('POST', '/endpoint');
     * $request->getMethod(); // 'POST'
     * $request->getUri();    // UriInterface mock for '/endpoint'
     * </code>
     *
     * @param string $method HTTP method for the request
     * @param string $uri    URI string for the request
     *
     * @return RequestInterface a PSR-7 request mock
     */
    public function mockRequest(string $method = 'GET', string $uri = '/'): RequestInterface
    {
        /** @var MockObject&RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);
        $request->method('getMethod')->willReturn($method);
        $request->method('getUri')->willReturn($this->mockUri($uri));

        return $request;
    }

    /**
     * Create a mock PSR-7 ResponseInterface.
     *
     * Configures getStatusCode() and optionally getBody().
     *
     * Usage:
     *
     * <code>
     * $stream = $this->mockStream('content');
     * $response = $this->mockResponse(404, $stream);
     * $response->getStatusCode(); // 404
     * $response->getBody()->getContents(); // 'content'
     * </code>
     *
     * @param int                         $status HTTP status code
     * @param null|StreamInterface|string $body   body content or stream
     *
     * @return ResponseInterface a mock PSR-7 response
     */
    public function mockResponse(int $status = 200, $body = null): ResponseInterface
    {
        /** @var MockObject&ResponseInterface $response */
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($status);

        if (null !== $body) {
            $stream = is_string($body) ? $this->mockStream($body) : $body;
            $response->method('getBody')->willReturn($stream);
        }

        return $response;
    }

    /**
     * Create a mock PSR-7 ServerRequestInterface.
     *
     * Configures getAttribute() and getHeaderLine() based on provided arrays.
     *
     * Usage:
     *
     * <code>
     * $request = $this->mockServerRequest(['user' => $user], ['Accept' => 'application/json']);
     * $request->getAttribute('user'); // $user
     * $request->getHeaderLine('Accept'); // 'application/json'
     * </code>
     *
     * @param array<string, mixed>  $attributes attributes to return
     * @param array<string, string> $headers    headers to return
     *
     * @return ServerRequestInterface a mock server request
     */
    public function mockServerRequest(array $attributes = [], array $headers = []): ServerRequestInterface
    {
        /** @var MockObject&ServerRequestInterface $request */
        $request = $this->createMock(ServerRequestInterface::class);

        $request->method('getAttribute')->willReturnCallback(fn (string $name, $default = null) => $attributes[$name] ?? $default);

        $request->method('getHeaderLine')->willReturnCallback(fn (string $name) => $headers[$name] ?? '');

        return $request;
    }

    /**
     * Create a mock PSR-7 StreamInterface.
     *
     * Configures __toString() and getContents(), and optionally write().
     *
     * Usage:
     *
     * <code>
     * $stream = $this->mockStream('data', ['write' => true]);
     * $stream->write('abc'); // returns length of 'data'
     * </code>
     *
     * @param string              $content content to return when reading the stream
     * @param array<string, bool> $methods Options to enable extra methods (e.g. 'write' => true).
     *
     * @return StreamInterface a mock stream
     */
    public function mockStream(string $content = '', array $methods = []): StreamInterface
    {
        /** @var MockObject&StreamInterface $stream */
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn($content);
        $stream->method('getContents')->willReturn($content);

        if (isset($methods['write']) && $methods['write']) {
            $stream->method('write')->willReturn(strlen($content));
        }

        return $stream;
    }

    /**
     * Create a mock PSR-7 UriInterface.
     *
     * Configures __toString() to return specified URI string.
     *
     * Usage:
     *
     * <code>
     * $uri = $this->mockUri('https://example.com');
     * (string) $uri; // 'https://example.com'
     * </code>
     *
     * @param string $uri URI string to return
     *
     * @return UriInterface a mock URI
     */
    public function mockUri(string $uri = '/'): UriInterface
    {
        /** @var MockObject&UriInterface $uriMock */
        $uriMock = $this->createMock(UriInterface::class);
        $uriMock->method('__toString')->willReturn($uri);

        return $uriMock;
    }
}
