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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prophecy;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use tomkyle\MockPsr\MockPsr18ClientTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class MockPsr18ClientTraitTest extends TestCase
{
    // SUT
    use MockPsr18ClientTrait;

    #[DataProvider('provideVariousResponses')]
    public function testMockClient($response)
    {
        $handler = $this->mockClient($response);
        $this->assertInstanceOf(ClientInterface::class, $handler);
    }

    public static function provideVariousResponses()
    {
        $response200 = (new Prophecy\Prophet())->prophesize(ResponseInterface::class);
        $response200->getStatusCode()->willReturn(200);
        $response400 = (new Prophecy\Prophet())->prophesize(ResponseInterface::class);
        $response400->getStatusCode()->willReturn(400);

        return [
            'Response with 200' => [$response200->reveal()],
            'Response with 400' => [$response400->reveal()],
            'No response defined' => [null],
        ];
    }
}
