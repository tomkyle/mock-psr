<?php
namespace tests;

use tomkyle\MockPsr\MockPsr11ContainerTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use Prophecy\Argument;

class MockPsr11ContainerTraitTest extends \PHPUnit\Framework\TestCase
{
    // SUT
    use MockPsr11ContainerTrait;


    #[DataProvider('provideContainerContentArray')]
    public function testMockContainer( $items )
    {
        $container = $this->mockContainer($items);
        $this->assertInstanceOf( ContainerInterface::class, $container);

        foreach($items as $key => $value) {
            $this->assertTrue( $container->has($key ));
            $this->assertEquals( $value, $container->get($key));
        }
    }


    public static function provideContainerContentArray()
    {
        return array(
            'Empty container' => [ array() ],
            'foo => bar' => [ array('foo' => 'bar', 'qux' => 'baz') ],
        );
    }


    public function testNotFoundException( )
    {
        $container = $this->mockContainer( array() );
        $this->assertInstanceOf( ContainerInterface::class, $container);

        $this->assertFalse( $container->has("foo"));
        $this->expectException(NotFoundExceptionInterface::class);
        $container->get("foo");
    }


}
