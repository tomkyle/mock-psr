<?php
namespace tests;

use tomkyle\MockPsr\MockPsr6CacheTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;
use Psr\Http\Message\ResponseInterface;
use Prophecy;

class MockPsr6CacheTraitTest extends \PHPUnit\Framework\TestCase
{
    // SUT
    use MockPsr6CacheTrait;



    public function testMockMissingCacheItem()
    {
        $cache_item = $this->mockMissingCacheItem("missing");

        $this->assertInstanceOf( CacheItemInterface::class, $cache_item);
        $this->assertFalse( $cache_item->isHit());

    }


    #[DataProvider('provideVariousCacheItemContent')]
    public function testMockCacheItem( $key, $content, $options )
    {
        $cache_item = $this->mockCacheItem($content, $options);

        $this->assertInstanceOf( CacheItemInterface::class, $cache_item);
        $this->assertEquals( $content, $cache_item->get());

        if (isset($options['isHit'])) {
            $isHit = (bool) $options['isHit'];
            if ($isHit) {
                $this->assertTrue( $cache_item->isHit());
            } else {
                $this->assertFalse( $cache_item->isHit());
            }
        }

        if (isset($options['expiresAfter'])) {
            $expires_value = $options['expiresAfter'];
            $expires_value = is_int($expires_value) ? $expires_value : 100;
            $cache_item->expiresAfter($expires_value);
        }
        if (isset($options['set'])) {
            $set_value = $options['set'];
            $set_value = is_string($set_value) ? $set_value : "foo";
            $cache_item->set($set_value);
        }

    }


    public static function provideVariousCacheItemContent()
    {
        return array(
            'foobar and getKey'            => [ 'foo', 'bar', array('getKey'       => true) ],
            'foobar and expiresAfter 99'   => [ 'foo', 'bar', array('expiresAfter' => 99) ],
            'foobar and expiresAfter TRUE' => [ 'foo', 'bar', array('expiresAfter' => true) ],
            'foobar and isHit'             => [ 'foo', 'bar', array('getKey'       => true, 'isHit' => true) ],
            'foobar and set'               => [ 'foo', 'bar', array('getKey'       => true, 'set'   => true) ],
        );
    }



    #[DataProvider('provideVariousCacheItems')]
    public function testMockCacheItemPool( $cache_item, $options )
    {
        $cache = $this->mockCacheItemPool($cache_item, $options);
        $this->assertInstanceOf( CacheItemPoolInterface::class, $cache);

        if ($options['save'] ?? false) {
            $cache->save($cache_item);
        }
        if (isset($options['clear'])) {
            $this->assertEquals($options['clear'], $cache->clear());
        }
        if ($cache_item instanceof CacheItemInterface) {
            $cache_key = $cache_item->getKey();
            $this->assertTrue($cache->hasItem($cache_key));
            $this->assertEquals($cache_item, $cache->getItem($cache_key));
        }
    }


    public static function provideVariousCacheItems()
    {

        $cache_item_mock = (new Prophecy\Prophet)->prophesize(CacheItemInterface::class);
        $cache_item_mock->get()->willReturn("QuxBaz");
        $cache_item_mock->getKey()->willReturn("qux");
        $cache_item = $cache_item_mock->reveal();

        $cache_item_with_certain_key_mock = (new Prophecy\Prophet)->prophesize(CacheItemInterface::class);
        $cache_item_with_certain_key_mock->get()->willReturn("FooBar");
        $cache_item_with_certain_key_mock->getKey()->willReturn("foo");
        $cache_item_with_certain_key = $cache_item_with_certain_key_mock->reveal();

        return array(
            'Empty cache w/o CacheItem'      => [ null, array() ],
            'CacheItem mock'                 => [ $cache_item, array() ],
            'CacheItem mock w/ certain key'  => [ $cache_item_with_certain_key, array() ],
            'CacheItem mock and save'        => [ $cache_item, array('save'  => true) ],
            'CacheItem mock and clear'       => [ $cache_item, array('clear' => true) ],
            'CacheItem per array'            => [ array(
                'one' => $cache_item,
                'two' => "ItemValue"
            ), array('clear' => true) ]
        );
    }

}
