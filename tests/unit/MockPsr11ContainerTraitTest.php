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
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use tomkyle\MockPsr\MockPsr11ContainerTrait;


class MockPsr11ContainerTraitTest extends TestCase
{
    #[DataProvider('provideContainerContentArray')]
    public function testMockContainer($items)
    {
        $sut = new class('test') extends TestCase {
            use MockPsr11ContainerTrait;
        };
        $container = $sut->mockContainer($items);
        $this->assertInstanceOf(ContainerInterface::class, $container);

        foreach ($items as $key => $value) {
            $this->assertTrue($container->has($key));
            $this->assertEquals($value, $container->get($key));
        }
    }

    public static function provideContainerContentArray()
    {
        return [
            'Empty container' => [[]],
            'foo => bar' => [['foo' => 'bar', 'qux' => 'baz']],
        ];
    }

    public function testNotFoundException()
    {
        $sut = new class('test') extends TestCase {
            use MockPsr11ContainerTrait;
        };
        $container = $sut->mockContainer([]);
        $this->assertInstanceOf(ContainerInterface::class, $container);

        $this->assertFalse($container->has('foo'));
        $this->expectException(NotFoundExceptionInterface::class);
        $container->get('foo');
    }
}
