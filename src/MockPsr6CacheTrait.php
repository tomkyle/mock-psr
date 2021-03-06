<?php

namespace tomkyle\MockPsr;

use Psr\Cache;
use Prophecy;

trait MockPsr6CacheTrait
{
    /**
     * @var string
     */
    protected $default_key_name = "keyname";

    /**
     * @param  CacheItemPoolInterface|array $cache_item
     * @param  array  $options              CacheItemPool configuration
     */
    public function mockCacheItemPool($cache_item = null, array $options = array())
    {
        $cache = (new Prophecy\Prophet)->prophesize(Cache\CacheItemPoolInterface::class);

        if ($cache_item instanceof Cache\CacheItemInterface) {
            $key = $cache_item->getKey();
            $key = ($key !== $this->default_key_name)
                 ? Prophecy\Argument::exact($key) : Prophecy\Argument::type('string');

            $cache->getItem($key)->willReturn($cache_item);
            $cache->hasItem($key)->willReturn(true);
        } elseif (is_array($cache_item)) {
            foreach ($cache_item as $key => $item) {
                if (!$item instanceof Cache\CacheItemInterface) {
                    $item = $this->mockCacheItem($item, [ 'getKey' => $key ]);
                }
                $key = $item->getKey();
                $cache->getItem(Prophecy\Argument::exact($key))->willReturn($item);
                $cache->hasItem(Prophecy\Argument::exact($key))->willReturn(true);
            }
        } elseif ($cache_item) {
            throw new \InvalidArgumentException("CacheItemInterface expected");
        }

        if ($options['save'] ?? false) {
            $cache->save(Prophecy\Argument::type(Cache\CacheItemInterface::class))->shouldBeCalled();
        }

        if (isset($options['clear'])) {
            $cache->clear()->shouldBeCalled()->willReturn((bool) $options['clear']);
        }

        if (isset($options['hasItem'])) {
            $cache->hasItem(Prophecy\Argument::type('string'))->willReturn((bool) $options['hasItem']);
        }


        return $cache->reveal();
    }




    public function mockCacheItem($item_content, array $options = array())
    {
        $cache_item = (new Prophecy\Prophet)->prophesize(Cache\CacheItemInterface::class);
        $cache_item->get()->willReturn($item_content);

        // if ($get_value = $options['getKey'] ?? false):
        $cache_item->getKey()->willReturn($options['getKey'] ?? $this->default_key_name);
        // endif;

        if (isset($options['isHit'])) {
            $isHit = (bool) $options['isHit'];
            $cache_item->isHit()->willReturn($isHit);
        }

        if (isset($options['set'])) {
            $set_value = $options['set'] ?? false;
            $set_value = is_string($set_value) ? $set_value : Prophecy\Argument::type('string');
            $cache_item->set($set_value)->willReturn($cache_item);
        }

        if (isset($options['expiresAfter'])) {
            $expires_value = $options['expiresAfter'];
            $expires_value = is_int($expires_value) ? $expires_value : Prophecy\Argument::type('int');
            $cache_item->expiresAfter($expires_value)->willReturn($cache_item);
        }

        return $cache_item->reveal();
    }



    public function mockMissingCacheItem($item_content, array $options = array())
    {
        return $this->mockCacheItem($item_content, array_merge($options, [
            'isHit' => false
        ]));
    }
}
