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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use tomkyle\MockPsr\MockPsr15RequestHandlerTrait;

class MockPsr15RequestHandlerTraitTest extends TestCase
{
    #[DataProvider('provideVariousResponses')]
    public function testMockRequestHandler($response)
    {
        $sut = new class('test') extends TestCase {
            use MockPsr15RequestHandlerTrait;
        };
        $handler = $sut->mockRequestHandler($response);
        $this->assertInstanceOf(RequestHandlerInterface::class, $handler);
    }

    public static function provideVariousResponses()
    {
        $factory = new class('factory') extends TestCase {
            use MockPsr15RequestHandlerTrait;
        };

        $response200 = $factory->createMock(ResponseInterface::class);
        $response200->method('getStatusCode')->willReturn(200);
        $response400 = $factory->createMock(ResponseInterface::class);
        $response400->method('getStatusCode')->willReturn(400);

        return [
            'Response with 200' => [$response200],
            'Response with 400' => [$response400],
            'No response defined' => [null],
        ];
    }

    #[DataProvider('provideVariousExceptionResponses')]
    public function testMockRequestHandlerWithExceptions($e)
    {
        $sut = new class('test') extends TestCase {
            use MockPsr15RequestHandlerTrait;
        };
        $handler = $sut->mockRequestHandler($e);
        $server_request = $sut->mockServerRequest();
        $this->expectException(get_class($e));
        $handler->handle($server_request);
    }

    public static function provideVariousExceptionResponses()
    {
        return [
            'Simple Exception' => [new \Exception('Exception!')],
            'RuntimeException' => [new \RuntimeException('RuntimeException!')],
        ];
    }
}
