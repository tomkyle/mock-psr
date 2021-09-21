<?php
namespace tests;

use tomkyle\MockPsr\MockPsr6CacheTrait;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class MockPsr6CacheTraitTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait,
        // SUT
        MockPsr6CacheTrait;



    public function testMockMissingCacheItem()
    {
        $cache_item = $this->mockMissingCacheItem("missing");

        $this->assertInstanceOf( CacheItemInterface::class, $cache_item);
        $this->assertFalse( $cache_item->isHit());

    }


    /**
     * @dataProvider provideVariousCacheItemContent
     */
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


    public function provideVariousCacheItemContent()
    {
        return array(
            'foobar and getKey' => [ 'foo', 'bar', array('getKey' => true) ],
            'foobar and expiresAfter 99' => [ 'foo', 'bar', array('expiresAfter' => 99) ],
            'foobar and expiresAfter TRUE' => [ 'foo', 'bar', array('expiresAfter' => true) ],
            'foobar and isHit' => [ 'foo', 'bar', array('getKey' => true, 'isHit' => true) ],
            'foobar and set' => [ 'foo', 'bar', array('getKey' => true, 'set' => true) ],
        );
    }



    /**
     * @dataProvider provideVariousCacheItems
     */
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

        $this->assertInstanceOf( CacheItemPoolInterface::class, $cache);

    }

    public function provideVariousCacheItems()
    {
        $cache_item = $this->mockCacheItem("QuxBaz");
        return array(
            'No item defined' => [ null, array() ],
            'CacheItem mock' => [ $cache_item, array() ],
            'CacheItem mock and save' => [ $cache_item, array('save' => true) ],
            'CacheItem mock and clear' => [ $cache_item, array('clear' => true) ],
            'CacheItem per array' => [ ['one' => $cache_item, 'two' => "ItemValue"], array('clear' => true) ]
        );
    }

}
