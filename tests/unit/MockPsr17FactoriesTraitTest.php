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


class MockPsr17FactoriesTraitTest extends TestCase
{
    public function testMockRequestFactory()
    {
        $sut = new class('test') extends TestCase {
            use MockPsr17FactoriesTrait;
        };
        $factory = $sut->mockRequestFactory();
        $this->assertInstanceOf(RequestFactoryInterface::class, $factory);

        $request = $factory->createRequest('GET', '/');
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testMockResponseFactory()
    {
        $sut = new class('test') extends TestCase {
            use MockPsr17FactoriesTrait;
        };
        $factory = $sut->mockResponseFactory();
        $this->assertInstanceOf(ResponseFactoryInterface::class, $factory);

        $response = $factory->createResponse();
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
