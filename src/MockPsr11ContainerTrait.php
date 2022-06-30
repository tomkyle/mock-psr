<?php

namespace tomkyle\MockPsr;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use Prophecy;

trait MockPsr11ContainerTrait
{

    public function mockContainer(array $items = array()): ContainerInterface
    {
        $NF = new class () extends \Exception implements NotFoundExceptionInterface {
        };
        $container_mock = (new Prophecy\Prophet)->prophesize(ContainerInterface::class);
        $container_mock->has(Prophecy\Argument::any())->willReturn(false);
        $container_mock->get(Prophecy\Argument::any())->willThrow($NF);

        foreach ($items as $key => $item) {
            $container_mock->has(Prophecy\Argument::exact($key))->willReturn(true);
            $container_mock->get(Prophecy\Argument::exact($key))->willReturn($item);
        }


        return $container_mock->reveal();
    }
}
