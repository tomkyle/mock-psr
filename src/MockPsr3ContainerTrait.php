<?php
namespace tomkyle\MockPsr;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

trait MockPsr3ContainerTrait
{
    use ProphecyTrait;

    public function mockContainer(array $items = array()) : ContainerInterface
    {
        $NF = new class() extends \Exception implements NotFoundExceptionInterface {
        };

        $container_mock = $this->prophesize(ContainerInterface::class);
        $container_mock->has(Argument::any())->willReturn(false);
        $container_mock->get(Argument::any())->willThrow($NF);

        foreach ($items as $key => $item) {
            $container_mock->has(Argument::exact($key))->willReturn(true);
            $container_mock->get(Argument::exact($key))->willReturn($item);
        }


        return $container_mock->reveal();
    }
}
