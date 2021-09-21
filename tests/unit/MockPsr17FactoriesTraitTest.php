<?php
namespace tests;

use tomkyle\MockPsr\MockPsr17FactoriesTrait;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class MockPsr17FactoriesTraitTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait,
        // SUT
        MockPsr17FactoriesTrait;


    public function testMockRequestFactory()
    {
        $factory = $this->mockRequestFactory();
        $this->assertInstanceOf( RequestFactoryInterface::class, $factory);

        $request = $factory->createRequest("GET", "/");
        $this->assertInstanceOf( RequestInterface::class, $request);
    }

    public function testMockResponseFactory()
    {
        $factory = $this->mockResponseFactory();
        $this->assertInstanceOf( ResponseFactoryInterface::class, $factory);

        $response = $factory->createResponse();
        $this->assertInstanceOf( ResponseInterface::class, $response);
    }

}
