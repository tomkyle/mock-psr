<?php
namespace tests;

use tomkyle\MockPsr\MockPsr7MessagesTrait;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

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


    public function testMockStream()
    {
        $stream = $this->mockStream("String");
        $this->assertInstanceOf( StreamInterface::class, $stream);

        $stream = $this->mockStream("String", ['write' => true]);
        $stream->write("Yay!");
    }




    /**
     * @dataProvider provideAttributesAndHeaders
     */
    public function testMockServerRequest($attributes, $headers)
    {
        $request = $this->mockServerRequest($attributes, $headers);

        $this->assertInstanceOf( ServerRequestInterface::class, $request);

        foreach ($attributes as $name => $value) {
            $this->assertEquals($value, $request->getAttribute($name));
        }
        foreach ($headers as $name => $value) {
            $this->assertEquals($value, $request->getHeaderLine($name));
        }

    }

    public function provideAttributesAndHeaders()
    {
        $attributes = array('foo' => 'bar');
        $headers = array('foo' => 'bar');

        return array(
            'Empty attributes and headers' => [ $attributes, $headers ]
        );
    }




    /**
     * @dataProvider provideReponseStatusCodes
     */
    public function testMockResponse($status, $body)
    {
        if (is_null($status)) {
            $response = $this->mockResponse();
            $status = 200;
        }
        else {
            $response = $this->mockResponse( $status, $body);
        }

        $this->assertInstanceOf( ResponseInterface::class, $response);
        $this->assertEquals( $status, $response->getStatusCode() );
    }

    public function provideReponseStatusCodes()
    {
        $body = "string";
        $stream = $this->mockStream("String");
        return array(
            'Response with 200, with body string' => [ 200, $body ],
            'Response with 200, with body stream' => [ 200, $stream ],
            'Response with 400, with body' => [ 400, $body ],
            'Response with 400, no body'      => [ 400, null ],
            'No status code defined, no body' => [ null, $body ]
        );
    }
}
