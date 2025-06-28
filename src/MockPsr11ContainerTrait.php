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
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

trait MockPsr11ContainerTrait
{
    /**
     * Create a mock PSR-11 ContainerInterface.
     *
     * Configures has() and get() based on provided items array.
     *
     * Usage:
     *
     * <code>
     * $container = $this->mockContainer(['service' => $instance]);
     * $container->has('service'); // true
     * $container->get('service'); // $instance
     * $container->get('unknown'); // throws NotFoundExceptionInterface
     * </code>
     *
     * @param array<string, mixed> $items service entries for the container
     *
     * @return ContainerInterface a mock container
     */
    public function mockContainer(array $items = []): ContainerInterface
    {
        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);

        $container->method('has')->willReturnCallback(fn (string $id) => array_key_exists($id, $items));

        $container->method('get')->willReturnCallback(function (string $id) use ($items) {
            if (!array_key_exists($id, $items)) {
                throw new class('Service not found') extends \Exception implements NotFoundExceptionInterface {};
            }

            return $items[$id];
        });

        return $container;
    }
}
