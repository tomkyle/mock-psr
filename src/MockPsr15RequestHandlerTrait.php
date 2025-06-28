<?php

/**
 * This file is part of tomkyle/mock-psr
 *
 * Mock common PSR standard components in PhpUnit tests
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tomkyle\MockPsr;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

trait MockPsr15RequestHandlerTrait
{
    use MockPsr7MessagesTrait;

    /**
     * Create a mock PSR-15 RequestHandler.
     *
     * Returns a mock implementation of RequestHandlerInterface that returns the
     * given ResponseInterface or throws the given exception when handling a request.
     *
     * Usage:
     *
     * <code>
     * $response = $this->mockResponse(404, 'Not Found');
     * $handler = $this->mockRequestHandler($response);
     * $handler->handle($request);
     * </code>
     *
     * @param null|ResponseInterface|\Throwable $response response to return or exception to throw
     *
     * @return RequestHandlerInterface a PSR-15 request handler mock
     */
    public function mockRequestHandler($response = null): RequestHandlerInterface
    {
        $response = $response ?: $this->mockResponse();

        /** @var MockObject&RequestHandlerInterface $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);

        if ($response instanceof ResponseInterface) {
            $handler->method('handle')->willReturn($response);
        } elseif ($response instanceof \Throwable) {
            $handler->method('handle')->willThrowException($response);
        }

        return $handler;
    }
}
