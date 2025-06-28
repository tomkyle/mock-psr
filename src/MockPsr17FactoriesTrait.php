<?php

/**
 * This file is part of tomkyle/mock-psr
 *
 * Traits for mocking common PSR components in PhpUnit tests
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tomkyle\MockPsr;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

trait MockPsr17FactoriesTrait
{
    use MockPsr7MessagesTrait;

    /**
     * Create a mock PSR-17 RequestFactoryInterface.
     *
     * Returns a factory mock whose createRequest() returns a specified RequestInterface.
     *
     * Usage:
     *
     * <code>
     * $request = $this->mockRequest('GET', '/');
     * $factory = $this->mockRequestFactory($request);
     * $factory->createRequest('GET', '/'); // returns $request
     * </code>
     *
     * @param null|RequestInterface $request request to be returned by createRequest()
     *
     * @return RequestFactoryInterface a mock request factory
     */
    public function mockRequestFactory(?RequestInterface $request = null): RequestFactoryInterface
    {
        $request = $request ?: $this->mockRequest('GET', '/');

        /** @var MockObject&RequestFactoryInterface $factory */
        $factory = $this->createMock(RequestFactoryInterface::class);
        $factory->method('createRequest')->willReturn($request);

        return $factory;
    }

    /**
     * Create a mock PSR-17 ResponseFactoryInterface.
     *
     * Returns a factory mock whose createResponse() returns a specified ResponseInterface.
     *
     * Usage:
     *
     * <code>
     * $response = $this->mockResponse(200, 'body');
     * $factory = $this->mockResponseFactory($response);
     * $factory->createResponse(200); // returns $response
     * </code>
     *
     * @param null|ResponseInterface $response response to be returned by createResponse()
     *
     * @return ResponseFactoryInterface a mock response factory
     */
    public function mockResponseFactory(?ResponseInterface $response = null): ResponseFactoryInterface
    {
        $response = $response ?: $this->mockResponse();

        /** @var MockObject&ResponseFactoryInterface $factory */
        $factory = $this->createMock(ResponseFactoryInterface::class);
        $factory->method('createResponse')->willReturn($response);

        return $factory;
    }
}
