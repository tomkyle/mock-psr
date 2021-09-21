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

        if (isset($options['isHit'])):
            $cache_item->isHit()->willReturn((bool) $options['isHit']);
        endif;

        if ($set_value = $options['set'] ?? false):
            if (is_string($set_value)):
                $cache_item->set($set_value)->shouldBeCalled(); else:
                $cache_item->set(Argument::any())->shouldBeCalled();
        endif;
        endif;

        if ($expires_value = $options['expiresAfter'] ?? false):
            if (is_int($expires_value)):
                $cache_item->expiresAfter($expires_value)->shouldBeCalled(); else:
                $cache_item->expiresAfter(Argument::any())->shouldBeCalled();
        endif;
        endif;

        return $cache_item->reveal();
    }



    public function mockMissingCacheItem(array $options = array())
    {
        return $this->createCacheItem(array_merge($options, [
            'isHit' => false
        ]));
    }
}
