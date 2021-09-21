<?php
namespace tests;

use tomkyle\MockPsr\MockPsr18ClientTrait;

use Psr\Http\Client\ClientInterface;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class MockPsr18ClientTraitTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait,
        // SUT
        MockPsr18ClientTrait;


    /**
     * @dataProvider provideVariousResponses
     */
    public function testMockClient( $response )
    {
        $handler = $this->mockClient($response);
        $this->assertInstanceOf( ClientInterface::class, $handler);
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
