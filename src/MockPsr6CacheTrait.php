<?php
namespace tomkyle\MockPsr;

use Psr\Cache;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

trait MockPsr6CacheTrait
{
    use ProphecyTrait;

    /**
     * @param  CacheItemPoolInterface|array $cache_item
     * @param  array  $options              CacheItemPool configuration
     */
    public function mockCacheItemPool($cache_item = null, array $options = array())
    {
        $cache = $this->prophesize(Cache\CacheItemPoolInterface::class);

        if ($cache_item instanceof Cache\CacheItemInterface) {
            $cache->getItem(Argument::type('string'))->willReturn($cache_item);
        } elseif (is_array($cache_item)) {
            foreach ($cache_item as $key => $item) {
                if (!$item instanceof Cache\CacheItemInterface) {
                    $item = $this->mockCacheItem($item, [ 'getKey' => $key ]);
                }
                $cache->getItem(Argument::exact($cache_item->getKey()))->willReturn($item);
            }
        } elseif ($cache_item) {
            throw new \InvalidArgumentException("CacheItemInterface expected");
        }

        if ($options['save'] ?? false) {
            $cache->save(Argument::any())->shouldBeCalled();
        }

        if (isset($options['clear'])) {
            $cache->clear()->willReturn((bool) $options['clear']);
        }

        if (isset($options['hasItem'])) {
            $cache->hasItem()->willReturn((bool) $options['hasItem']);
        }


        return $cache->reveal();
    }




    public function mockCacheItem($item_content, array $options = array())
    {
        $cache_item = $this->prophesize(Cache\CacheItemInterface::class);
        $cache_item->get()->willReturn($item_content);

        if ($get_value = $options['getKey'] ?? false):
            $cache_item->getKey()->willReturn($get_value);
        endif;

        if (isset($options['isHit'])) {
            $isHit = (bool) $options['isHit'];
            $cache_item->isHit()->willReturn($isHit);
        }

        if (isset($options['set'])) {
            $set_value = $options['set'] ?? false;
            $set_value = is_string($set_value) ? $set_value : Argument::type('string');
            $cache_item->set($set_value)->shouldBeCalled();
        }

        if (isset($options['expiresAfter'])) {
            $expires_value = $options['expiresAfter'];
            $expires_value = is_int($expires_value) ? $expires_value : Argument::type('int');
            $cache_item->expiresAfter($expires_value)->shouldBeCalled();
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
