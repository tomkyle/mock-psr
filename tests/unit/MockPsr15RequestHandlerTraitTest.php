<?php

/**
 * This file is part of tomkyle/mock-psr
 *
 * Traits for mocking common PSR components in PhpUnit tests
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prophecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use tomkyle\MockPsr\MockPsr15RequestHandlerTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class MockPsr15RequestHandlerTraitTest extends TestCase
{
    // SUT
    use MockPsr15RequestHandlerTrait;

    protected $responseMock;

    #[DataProvider('provideVariousResponses')]
    public function testMockRequestHandler($response)
    {
        $handler = $this->mockRequestHandler($response);
        $this->assertInstanceOf(RequestHandlerInterface::class, $handler);
    }

    public static function provideVariousResponses()
    {
        $response200 = (new Prophecy\Prophet())->prophesize(ResponseInterface::class);
        $response200->getStatusCode()->willReturn(200);
        $response400 = (new Prophecy\Prophet())->prophesize(ResponseInterface::class);
        $response400->getStatusCode()->willReturn(400);

        return [
            'Response with 200' => [$response200->reveal()],
            'Response with 400' => [$response400->reveal()],
            'No response defined' => [null],
        ];
    }

    #[DataProvider('provideVariousExceptionResponses')]
    public function testMockRequestHandlerWithExceptions($e)
    {
        $handler = $this->mockRequestHandler($e);
        $server_request = $this->mockServerRequest();
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
