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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

trait MockPsr15RequestHandlerTrait
{
    use MockPsr7MessagesTrait;

    public function mockRequestHandler($response = null): RequestHandlerInterface
    {
        $response = $response ?: $this->mockResponse();
        $objectProphecy = (new Prophecy\Prophet())->prophesize(RequestHandlerInterface::class);

        if ($response instanceof ResponseInterface) {
            $objectProphecy->handle(Prophecy\Argument::type(ServerRequestInterface::class))->willReturn($response);
        } elseif ($response instanceof \Throwable) {
            $objectProphecy->handle(Prophecy\Argument::type(ServerRequestInterface::class))->willThrow($response);
        }

        return $objectProphecy->reveal();
    }
}
