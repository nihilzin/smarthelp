<?php

/**
 * ---------------------------------------------------------------------
 *
 * Powered by Urich Souza 
 *
 * https://github.com/nihilzin
 *
 * @copyright 2023 Urich Souza and contributors.
 * 
 * ---------------------------------------------------------------------
 */

namespace Glpi\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * Cache class used to be able to use a symfony/cache instance in overrided laminas-i18n Translator service.
 *
 * /!\ For internal use only.
 */
class I18nCache
{
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getItem($key)
    {
        return $this->cache->get($key);
    }

    public function setItem($key, $value)
    {
        return $this->cache->set($key, $value);
    }

    public function removeItem($key)
    {
        return $this->cache->delete($key);
    }
}
