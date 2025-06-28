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

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

trait MockPsr6CacheTrait
{
    /**
     * Create a mock PSR-6 CacheItemInterface.
     *
     * Returns a mock cache item with a specified stored value and hit status.
     *
     * Usage:
     *
     * <code>
     * $item = $this->mockCacheItem('value', ['isHit' => true, 'getKey' => true]);
     * $item->get(); // 'value'
     * $item->isHit(); // true
     * </code>
     *
     * @param mixed $value   value to be returned by get()
     * @param array $options optional settings: 'isHit' => bool, 'getKey' => bool
     *
     * @return CacheItemInterface a PSR-6 cache item mock
     */
    public function mockCacheItem($value = null, array $options = []): CacheItemInterface
    {
        /** @var CacheItemInterface&MockObject $item */
        $item = $this->createMock(CacheItemInterface::class);

        $item->method('get')->willReturn($value);
        $item->method('isHit')->willReturn($options['isHit'] ?? true);

        if (isset($options['getKey'])) {
            $item->method('getKey')->willReturn('test-key');
        }

        $item->method('set')->willReturnSelf();
        $item->method('expiresAt')->willReturnSelf();
        $item->method('expiresAfter')->willReturnSelf();

        return $item;
    }

    /**
     * Create a mock PSR-6 CacheItemInterface for a missing cache entry.
     *
     * Returns a mock cache item where isHit() is false and get() returns null.
     *
     * Usage:
     *
     * <code>
     * $item = $this->mockMissingCacheItem('foo');
     * $item->isHit(); // false
     * $item->get(); // null
     * $item->getKey(); // 'foo'
     * </code>
     *
     * @param string $key cache key for the item
     *
     * @return CacheItemInterface a cache miss item mock
     */
    public function mockMissingCacheItem(string $key = 'missing'): CacheItemInterface
    {
        /** @var CacheItemInterface&MockObject $item */
        $item = $this->createMock(CacheItemInterface::class);

        $item->method('isHit')->willReturn(false);
        $item->method('get')->willReturn(null);
        $item->method('getKey')->willReturn($key);
        $item->method('set')->willReturnSelf();
        $item->method('expiresAt')->willReturnSelf();
        $item->method('expiresAfter')->willReturnSelf();

        return $item;
    }

    /**
     * Create a mock PSR-6 CacheItemPoolInterface.
     *
     * Returns a mock cache pool that will return specified items or cache misses.
     *
     * Usage:
     *
     * <code>
     * $pool = $this->mockCacheItemPool(['foo' => 'bar'], ['clear' => false]);
     * $pool->getItem('foo')->get(); // 'bar'
     * $pool->hasItem('baz'); // false
     * </code>
     *
     * @param null|array|CacheItemInterface $items   array of key => value or a CacheItemInterface instance
     * @param array                         $options optional settings: 'clear' => bool for clear() behavior
     *
     * @return CacheItemPoolInterface a PSR-6 cache pool mock
     */
    public function mockCacheItemPool($items = null, array $options = []): CacheItemPoolInterface
    {
        /** @var CacheItemPoolInterface&MockObject $pool */
        $pool = $this->createMock(CacheItemPoolInterface::class);

        if (is_array($items)) {
            $pool->method('hasItem')->willReturnCallback(fn (string $key) => array_key_exists($key, $items));

            $pool->method('getItem')->willReturnCallback(function (string $key) use ($items) {
                if (array_key_exists($key, $items)) {
                    $value = $items[$key];
                    if ($value instanceof CacheItemInterface) {
                        return $value;
                    }

                    return $this->mockCacheItem($value);
                }

                return $this->mockMissingCacheItem($key);
            });
        } elseif ($items instanceof CacheItemInterface) {
            $pool->method('hasItem')->willReturn(true);
            $pool->method('getItem')->willReturn($items);
        } else {
            $pool->method('hasItem')->willReturn(false);
            $pool->method('getItem')->willReturnCallback(fn (string $key) => $this->mockMissingCacheItem($key));
        }

        $pool->method('save')->willReturn(true);
        $pool->method('saveDeferred')->willReturn(true);
        $pool->method('commit')->willReturn(true);
        $pool->method('deleteItem')->willReturn(true);
        $pool->method('deleteItems')->willReturn(true);
        $pool->method('clear')->willReturn($options['clear'] ?? true);

        return $pool;
    }
}
