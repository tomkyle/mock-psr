<?php

/**
 * This file is part of tomkyle/mock-psr
 *
 * Mock common PSR standard components in PhpUnit tests
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use tomkyle\MockPsr\MockPsr7MessagesTrait;

class MockPsr7MessagesTraitTest extends TestCase
{
    #[DataProvider('provideMethodsAndUris')]
    public function testMockRequest($method, $uri)
    {
        $sut = new class('test') extends TestCase {
            use MockPsr7MessagesTrait;
        };
        $request = $sut->mockRequest($method, $uri);

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertEquals($method, $request->getMethod());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());
    }

    public static function provideMethodsAndUris()
    {
        $method = 'GET';
        $uri = '/';

        return [
            'GET /home' => [$method, $uri],
        ];
    }

    public function testMockStream()
    {
        $sut = new class('test') extends TestCase {
            use MockPsr7MessagesTrait;
        };
        $stream = $sut->mockStream('String');
        $this->assertInstanceOf(StreamInterface::class, $stream);

        $stream = $sut->mockStream('String', ['write' => true]);
        $stream->write('Yay!');
    }

    #[DataProvider('provideAttributesAndHeaders')]
    public function testMockServerRequest($attributes, $headers)
    {
        $sut = new class('test') extends TestCase {
            use MockPsr7MessagesTrait;
        };
        $request = $sut->mockServerRequest($attributes, $headers);

        $this->assertInstanceOf(ServerRequestInterface::class, $request);

        foreach ($attributes as $name => $value) {
            $this->assertEquals($value, $request->getAttribute($name));
        }
        foreach ($headers as $name => $value) {
            $this->assertEquals($value, $request->getHeaderLine($name));
        }
    }

    public static function provideAttributesAndHeaders()
    {
        $attributes = ['foo' => 'bar'];
        $headers = ['foo' => 'bar'];

        return [
            'Empty attributes and headers' => [$attributes, $headers],
        ];
    }

    #[DataProvider('provideReponseStatusCodes')]
    public function testMockResponse($status, $body)
    {
        $sut = new class('test') extends TestCase {
            use MockPsr7MessagesTrait;
        };

        if (is_null($status)) {
            $response = $sut->mockResponse();
            $status = 200;
        } else {
            $response = $sut->mockResponse($status, $body);
        }

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($status, $response->getStatusCode());
    }

    public static function provideReponseStatusCodes()
    {
        $body = 'string';
        $factory = new class('factory') extends TestCase {
            use MockPsr7MessagesTrait;
        };
        $stream = $factory->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn($body);

        return [
            'Response with 200, with body string' => [200, $body],
            'Response with 200, with body stream' => [200, $stream],
            'Response with 400, with body' => [400, $body],
            'Response with 400, no body' => [400, null],
            'No status code defined, no body' => [null, $body],
        ];
    }
}
