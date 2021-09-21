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



    /**
     * @dataProvider provideVariousCacheItemContent
     */
    public function testMockCacheItem( $key, $content )
    {
        $cache_item = $this->mockCacheItem($content);
        $this->assertInstanceOf( CacheItemInterface::class, $cache_item);
        $this->assertEquals( $content, $cache_item->get());
    }

    public function provideVariousCacheItemContent()
    {
        return array(
            'foobar' => [ 'foo', 'bar' ]
        );
    }



    /**
     * @dataProvider provideVariousCacheItems
     */
    public function testMockCacheItemPool( $cache_item )
    {
        $cache = $this->mockCacheItemPool($cache_item);
        $this->assertInstanceOf( CacheItemPoolInterface::class, $cache);
    }

    public function provideVariousCacheItems()
    {
        $cache_item = $this->mockCacheItem("QuxBaz");
        return array(
            'No item defined' => [ null ],
            'CacheItem mock' => [ $cache_item ]
        );
    }

}
