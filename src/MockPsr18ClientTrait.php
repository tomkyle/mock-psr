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
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

trait MockPsr18ClientTrait
{
    use MockPsr7MessagesTrait;

    /**
     * Create a mock PSR-18 HTTP client.
     *
     * Returns a mock implementation of ClientInterface that returns the
     * given ResponseInterface when sendRequest() is called.
     *
     * Usage:
     *
     * <code>
     * $response = $this->mockResponse(200, 'body');
     * $client = $this->mockClient($response);
     * $client->sendRequest($request);
     * </code>
     *
     * @param null|ResponseInterface $response response to return
     *
     * @return ClientInterface a PSR-18 HTTP client mock
     */
    public function mockClient(?ResponseInterface $response = null): ClientInterface
    {
        $response = $response ?: $this->mockResponse();

        /** @var ClientInterface&MockObject $client */
        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        return $client;
    }
}
