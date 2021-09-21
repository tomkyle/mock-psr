<?php
namespace tests;

use tomkyle\MockPsr\MockPsr15RequestHandlerTrait;
use tomkyle\MockPsr\MockPsr7MessagesTrait;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class MockPsr15RequestHandlerTraitTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait,
        // SUT
        MockPsr15RequestHandlerTrait;


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
}
