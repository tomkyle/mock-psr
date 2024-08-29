<?php
namespace tests;

use tomkyle\MockPsr\MockPsr18ClientTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Prophecy;
use Prophecy\Argument;

class MockPsr18ClientTraitTest extends \PHPUnit\Framework\TestCase
{
    // SUT
    use MockPsr18ClientTrait;


    #[DataProvider('provideVariousResponses')]
    public function testMockClient( $response )
    {
        $handler = $this->mockClient($response);
        $this->assertInstanceOf( ClientInterface::class, $handler);
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
}
