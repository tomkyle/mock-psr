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

use Prophecy;
use Psr\Cache;

trait MockPsr6CacheTrait
{
    /**
     * @var string
     */
    protected $default_key_name = 'keyname';

    /**
     * @param array|CacheItemPoolInterface $cache_item
     * @param array                        $options    CacheItemPool configuration
     */
    public function mockCacheItemPool($cache_item = null, array $options = [])
    {
        $objectProphecy = (new Prophecy\Prophet())->prophesize(Cache\CacheItemPoolInterface::class);

        if ($cache_item instanceof Cache\CacheItemInterface) {
            $key = $cache_item->getKey();
            $key = ($key !== $this->default_key_name)
                 ? Prophecy\Argument::exact($key) : Prophecy\Argument::type('string');

            $objectProphecy->getItem($key)->willReturn($cache_item);
            $objectProphecy->hasItem($key)->willReturn(true);
        } elseif (is_array($cache_item)) {
            foreach ($cache_item as $key => $item) {
                if (!$item instanceof Cache\CacheItemInterface) {
                    $item = $this->mockCacheItem($item, ['getKey' => $key]);
                }

                $key = $item->getKey();
                $objectProphecy->getItem(Prophecy\Argument::exact($key))->willReturn($item);
                $objectProphecy->hasItem(Prophecy\Argument::exact($key))->willReturn(true);
            }
        } elseif ($cache_item) {
            throw new \InvalidArgumentException('CacheItemInterface expected');
        }

        if ($options['save'] ?? false) {
            $objectProphecy->save(Prophecy\Argument::type(Cache\CacheItemInterface::class))->shouldBeCalled();
        }

        if (isset($options['clear'])) {
            $objectProphecy->clear()->shouldBeCalled()->willReturn((bool) $options['clear']);
        }

        if (isset($options['hasItem'])) {
            $objectProphecy->hasItem(Prophecy\Argument::type('string'))->willReturn((bool) $options['hasItem']);
        }

        return $objectProphecy->reveal();
    }

    public function mockCacheItem($item_content, array $options = [])
    {
        $objectProphecy = (new Prophecy\Prophet())->prophesize(Cache\CacheItemInterface::class);
        $objectProphecy->get()->willReturn($item_content);

        // if ($get_value = $options['getKey'] ?? false):
        $objectProphecy->getKey()->willReturn($options['getKey'] ?? $this->default_key_name);
        // endif;

        if (isset($options['isHit'])) {
            $isHit = (bool) $options['isHit'];
            $objectProphecy->isHit()->willReturn($isHit);
        }

        if (isset($options['set'])) {
            $set_value = $options['set'] ?? false;
            $set_value = is_string($set_value) ? $set_value : Prophecy\Argument::type('string');
            $objectProphecy->set($set_value)->willReturn($objectProphecy);
        }

        if (isset($options['expiresAfter'])) {
            $expires_value = $options['expiresAfter'];
            $expires_value = is_int($expires_value) ? $expires_value : Prophecy\Argument::type('int');
            $objectProphecy->expiresAfter($expires_value)->willReturn($objectProphecy);
        }

        return $objectProphecy->reveal();
    }

    public function mockMissingCacheItem($item_content, array $options = [])
    {
        return $this->mockCacheItem($item_content, array_merge($options, [
            'isHit' => false,
        ]));
    }
}
