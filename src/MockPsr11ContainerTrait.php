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
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

trait MockPsr11ContainerTrait
{
    public function mockContainer(array $items = []): ContainerInterface
    {
        $NF = new class extends \Exception implements NotFoundExceptionInterface {};
        $objectProphecy = (new Prophecy\Prophet())->prophesize(ContainerInterface::class);
        $objectProphecy->has(Prophecy\Argument::any())->willReturn(false);
        $objectProphecy->get(Prophecy\Argument::any())->willThrow($NF);

        foreach ($items as $key => $item) {
            $objectProphecy->has(Prophecy\Argument::exact($key))->willReturn(true);
            $objectProphecy->get(Prophecy\Argument::exact($key))->willReturn($item);
        }

        return $objectProphecy->reveal();
    }
}
