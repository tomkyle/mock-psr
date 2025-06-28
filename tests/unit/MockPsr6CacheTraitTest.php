<?php

/**
 * This file is part of tomkyle/mock-psr
 *
 * Mock common PSR standard components in PhpUnit tests
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use tomkyle\MockPsr\MockPsr6CacheTrait;

class MockPsr6CacheTraitTest extends TestCase
{
    public function testMockMissingCacheItem()
    {
        $sut = new class('test') extends TestCase {
            use MockPsr6CacheTrait;
        };
        $cache_item = $sut->mockMissingCacheItem('missing');

        $this->assertInstanceOf(CacheItemInterface::class, $cache_item);
        $this->assertFalse($cache_item->isHit());
    }

    #[DataProvider('provideVariousCacheItemContent')]
    public function testMockCacheItem($key, $content, $options)
    {
        $sut = new class('test') extends TestCase {
            use MockPsr6CacheTrait;
        };
        $cache_item = $sut->mockCacheItem($content, $options);

        $this->assertInstanceOf(CacheItemInterface::class, $cache_item);
        $this->assertEquals($content, $cache_item->get());

        if (isset($options['isHit'])) {
            $isHit = (bool) $options['isHit'];
            if ($isHit) {
                $this->assertTrue($cache_item->isHit());
            } else {
                $this->assertFalse($cache_item->isHit());
            }
        }

        if (isset($options['expiresAfter'])) {
            $expires_value = $options['expiresAfter'];
            $expires_value = is_int($expires_value) ? $expires_value : 100;
            $cache_item->expiresAfter($expires_value);
        }
        if (isset($options['set'])) {
            $set_value = $options['set'];
            $set_value = is_string($set_value) ? $set_value : 'foo';
            $cache_item->set($set_value);
        }
    }

    public static function provideVariousCacheItemContent()
    {
        return [
            'foobar and getKey' => ['foo', 'bar', ['getKey' => true]],
            'foobar and expiresAfter 99' => ['foo', 'bar', ['expiresAfter' => 99]],
            'foobar and expiresAfter TRUE' => ['foo', 'bar', ['expiresAfter' => true]],
            'foobar and isHit' => ['foo', 'bar', ['getKey' => true, 'isHit' => true]],
            'foobar and set' => ['foo', 'bar', ['getKey' => true, 'set' => true]],
        ];
    }

    #[DataProvider('provideVariousCacheItems')]
    public function testMockCacheItemPool($cache_item, $options)
    {
        $sut = new class('test') extends TestCase {
            use MockPsr6CacheTrait;
        };
        $cache = $sut->mockCacheItemPool($cache_item, $options);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $cache);

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
        $factory = new class('factory') extends TestCase {
            use MockPsr6CacheTrait;
        };

        $cache_item_mock = $factory->createMock(CacheItemInterface::class);
        $cache_item_mock->method('get')->willReturn('QuxBaz');
        $cache_item_mock->method('getKey')->willReturn('qux');
        $cache_item = $cache_item_mock;

        $cache_item_with_certain_key_mock = $factory->createMock(CacheItemInterface::class);
        $cache_item_with_certain_key_mock->method('get')->willReturn('FooBar');
        $cache_item_with_certain_key_mock->method('getKey')->willReturn('foo');
        $cache_item_with_certain_key = $cache_item_with_certain_key_mock;

        return [
            'Empty cache w/o CacheItem' => [null, []],
            'CacheItem mock' => [$cache_item, []],
            'CacheItem mock w/ certain key' => [$cache_item_with_certain_key, []],
            'CacheItem mock and save' => [$cache_item, ['save' => true]],
            'CacheItem mock and clear' => [$cache_item, ['clear' => true]],
            'CacheItem per array' => [[
                'one' => $cache_item,
                'two' => 'ItemValue',
            ], ['clear' => true]],
        ];
    }
}
