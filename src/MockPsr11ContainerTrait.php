<?php

namespace tomkyle\MockPsr;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use Prophecy\Argument;

trait MockPsr11ContainerTrait
{

    public function mockContainer(array $items = array()): ContainerInterface
    {
        $NF = new class () extends \Exception implements NotFoundExceptionInterface {
        };
        $prophet = new \Prophecy\Prophet;
        $container_mock = $prophet->prophesize(ContainerInterface::class);
        $container_mock->has(Argument::any())->willReturn(false);
        $container_mock->get(Argument::any())->willThrow($NF);

        foreach ($items as $key => $item) {
            $container_mock->has(Argument::exact($key))->willReturn(true);
            $container_mock->get(Argument::exact($key))->willReturn($item);
        }


        return $container_mock->reveal();
    }
}
