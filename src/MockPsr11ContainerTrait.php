<?php

namespace tomkyle\MockPsr;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use Prophecy;

trait MockPsr11ContainerTrait
{

    public function mockContainer(array $items = []): ContainerInterface
    {
        $NF = new class () extends \Exception implements NotFoundExceptionInterface {
        };
        $objectProphecy = (new Prophecy\Prophet)->prophesize(ContainerInterface::class);
        $objectProphecy->has(Prophecy\Argument::any())->willReturn(false);
        $objectProphecy->get(Prophecy\Argument::any())->willThrow($NF);

        foreach ($items as $key => $item) {
            $objectProphecy->has(Prophecy\Argument::exact($key))->willReturn(true);
            $objectProphecy->get(Prophecy\Argument::exact($key))->willReturn($item);
        }


        return $objectProphecy->reveal();
    }
}
