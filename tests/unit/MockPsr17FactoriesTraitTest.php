<?php

/**
 * This file is part of tomkyle/mock-psr
 *
 * Traits for mocking common PSR components in PhpUnit tests
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use tomkyle\MockPsr\MockPsr17FactoriesTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class MockPsr17FactoriesTraitTest extends TestCase
{
    // SUT
    use MockPsr17FactoriesTrait;

    public function testMockRequestFactory()
    {
        $factory = $this->mockRequestFactory();
        $this->assertInstanceOf(RequestFactoryInterface::class, $factory);

        $request = $factory->createRequest('GET', '/');
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testMockResponseFactory()
    {
        $factory = $this->mockResponseFactory();
        $this->assertInstanceOf(ResponseFactoryInterface::class, $factory);

        $response = $factory->createResponse();
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
