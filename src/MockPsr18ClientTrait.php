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
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait MockPsr18ClientTrait
{
    use MockPsr7MessagesTrait;

    public function mockClient(?ResponseInterface $response = null): ClientInterface
    {
        $response = $response ?: $this->mockResponse();

        $objectProphecy = (new Prophecy\Prophet())->prophesize(ClientInterface::class);
        $objectProphecy->sendRequest(Prophecy\Argument::type(RequestInterface::class))->willReturn($response);

        return $objectProphecy->reveal();
    }
}
