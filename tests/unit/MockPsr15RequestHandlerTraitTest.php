<?php
namespace tests;

use PHPUnit\Framework\Attributes\DataProvider;
use tomkyle\MockPsr\MockPsr15RequestHandlerTrait;
use tomkyle\MockPsr\MockPsr7MessagesTrait;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Prophecy;
use Prophecy\Argument;

class MockPsr15RequestHandlerTraitTest extends \PHPUnit\Framework\TestCase
{
    // SUT
    use MockPsr15RequestHandlerTrait;

    protected $responseMock;


    #[DataProvider('provideVariousResponses')]
    public function testMockRequestHandler( $response )
    {
        $handler = $this->mockRequestHandler($response);
        $this->assertInstanceOf( RequestHandlerInterface::class, $handler);
    }

    public static function provideVariousResponses()
    {
        $response200 = (new Prophecy\Prophet)->prophesize(ResponseInterface::class);
        $response200->getStatusCode()->willReturn(200);
        $response400 = (new Prophecy\Prophet)->prophesize(ResponseInterface::class);
        $response400->getStatusCode()->willReturn(400);

        return array(
            'Response with 200' => [ $response200->reveal() ],
            'Response with 400' => [ $response400->reveal() ],
            'No response defined' => [ null ]
        );
    }


    #[DataProvider('provideVariousExceptionResponses')]
    public function testMockRequestHandlerWithExceptions( $e )
    {
        $handler = $this->mockRequestHandler($e);
        $server_request = $this->mockServerRequest();
        $this->expectException( get_class($e) );
        $handler->handle( $server_request );
    }

    public static function provideVariousExceptionResponses()
    {
        return array(
            'Simple Exception' => [ new \Exception("Exception!") ],
            'RuntimeException' => [ new \RuntimeException("RuntimeException!") ],
        );
    }
}
