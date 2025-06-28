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
use Psr\Http\Client\ClientInterface;
use tomkyle\MockPsr\MockPsr18ClientTrait;
use tomkyle\MockPsr\MockPsr7MessagesTrait;


class MockPsr18ClientTraitTest extends TestCase
{
    #[DataProvider('provideVariousResponses')]
    public function testMockFactory($response)
    {
        $sut = new class('test') extends TestCase {
            use MockPsr18ClientTrait;
        };
        $handler = $sut->mockClient($response);
        $this->assertInstanceOf(ClientInterface::class, $handler);
    }

    public static function provideVariousResponses()
    {
        $factory = new class('factory') extends TestCase {
            use MockPsr7MessagesTrait;
        };

        $response200 = $factory->mockResponse(200);
        $response400 = $factory->mockResponse(400);

        return [
            'Response with 200' => [$response200],
            'Response with 400' => [$response400],
            'No response defined' => [null],
        ];
    }
}
