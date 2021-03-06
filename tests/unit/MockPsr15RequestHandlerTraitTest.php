<?php
namespace tests;

use tomkyle\MockPsr\MockPsr15RequestHandlerTrait;
use tomkyle\MockPsr\MockPsr7MessagesTrait;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Prophecy\Argument;

class MockPsr15RequestHandlerTraitTest extends \PHPUnit\Framework\TestCase
{
    // SUT
    use MockPsr15RequestHandlerTrait;


    /**
     * @dataProvider provideVariousResponses
     */
    public function testMockRequestHandler( $response )
    {
        $handler = $this->mockRequestHandler($response);
        $this->assertInstanceOf( RequestHandlerInterface::class, $handler);
    }

    public function provideVariousResponses()
    {
        return array(
            'Response with 200' => [ $this->mockResponse() ],
            'Response with 400' => [ $this->mockResponse(400) ],
            'No response defined' => [ null ]
        );
    }


    /**
     * @dataProvider provideVariousExceptionResponses
     */
    public function testMockRequestHandlerWithExceptions( $e )
    {
        $handler = $this->mockRequestHandler($e);
        $server_request = $this->mockServerRequest();
        $this->expectException( get_class($e) );
        $handler->handle( $server_request );
    }

    public function provideVariousExceptionResponses()
    {
        return array(
            'Simple Exception' => [ new \Exception("Exception!") ],
            'RuntimeException' => [ new \RuntimeException("RuntimeException!") ],
        );
    }
}
