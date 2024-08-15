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

namespace Glpi\Application\View\Extension;

use Entity;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @since 10.0.0
 */
class ConfigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('config', [$this, 'config']),
            new TwigFunction('entity_config', [$this, 'getEntityConfig']),
        ];
    }

    /**
     * Get GLPI configuration value.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function config(string $key)
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        return $CFG_GLPI[$key] ?? null;
    }

    /**
     * Get entity configuration value.
     *
     * @param string        $key              Configuration key.
     * @param int           $entity_id        Entity ID.
     * @param mixed         $default_value    Default value.
     * @param null|string   $inheritence_key  Key to use for inheritence check if different than key used to get value.
     *
     * @return mixed
     */
    public function getEntityConfig(string $key, int $entity_id, $default_value = -2, ?string $inheritence_key = null)
    {
        if ($inheritence_key === null) {
            $inheritence_key = $key;
        }

        return Entity::getUsedConfig($inheritence_key, $entity_id, $key, $default_value);
    }
}
