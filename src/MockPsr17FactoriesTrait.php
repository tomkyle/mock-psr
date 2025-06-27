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

use Prophecy;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

trait MockPsr17FactoriesTrait
{
    use MockPsr7MessagesTrait;

    public function mockRequestFactory(?RequestInterface $request = null): RequestFactoryInterface
    {
        $request = $request ?: $this->mockRequest('GET', '/');
        $objectProphecy = (new Prophecy\Prophet())->prophesize(RequestFactoryInterface::class);
        $objectProphecy->createRequest(Prophecy\Argument::type('string'), Prophecy\Argument::any())->willReturn($request);

        return $objectProphecy->reveal();
    }

    public function mockResponseFactory(?ResponseInterface $response = null): ResponseFactoryInterface
    {
        $response = $response ?: $this->mockResponse();
        $objectProphecy = (new Prophecy\Prophet())->prophesize(ResponseFactoryInterface::class);
        $objectProphecy->createResponse()->willReturn($response);
        $objectProphecy->createResponse(Prophecy\Argument::type('int'), Prophecy\Argument::any())->willReturn($response);

        return $objectProphecy->reveal();
    }
}
