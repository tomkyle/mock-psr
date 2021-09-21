<?php
namespace tests;

use tomkyle\MockPsr\MockPsr7MessagesTrait;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class MockPsr7MessagesTraitTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait,

        // SUT
        MockPsr7MessagesTrait;


    /**
     * @dataProvider provideMethodsAndUris
     */
    public function testMockRequest($method, $uri)
    {
        $request = $this->mockRequest($method, $uri);

        $this->assertInstanceOf( RequestInterface::class, $request);
        $this->assertEquals( $method, $request->getMethod());
        $this->assertInstanceOf( UriInterface::class, $request->getUri());
    }

    public function provideMethodsAndUris()
    {
        $method = "GET";
        $uri = "/";

        return array(
            'GET /home' => [ $method, $uri]
        );
    }



    /**
     * @dataProvider provideAttributesAndHeaders
     */
    public function testMockServerRequest($attributes, $headers)
    {
        $request = $this->mockServerRequest($attributes, $headers);

        $this->assertInstanceOf( ServerRequestInterface::class, $request);
    }

    public function provideAttributesAndHeaders()
    {
        $attributes = array();
        $headers = array();

        return array(
            'Empty attributes and headers' => [ $attributes, $headers ]
        );
    }




    /**
     * @dataProvider provideReponseStatusCodes
     */
    public function testMockResponse($status)
    {
        if (is_null($status)) {
            $response = $this->mockResponse();
            $status = 200;
        }
        else {
            $response = $this->mockResponse( $status);
        }

        $this->assertInstanceOf( ResponseInterface::class, $response);
        $this->assertEquals( $status, $response->getStatusCode() );
    }

    public function provideReponseStatusCodes()
    {
        return array(
            'Response with 200' => [ 200 ],
            'Response with 400' => [ 200 ],
            'No status code defined' => [ null ]
        );
    }
}
