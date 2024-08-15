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

use Plugin;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @since 10.0.0
 */
class PluginExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('call_plugin_hook', [$this, 'callPluginHook']),
            new TwigFunction('call_plugin_hook_func', [$this, 'callPluginHookFunction']),
            new TwigFunction('get_plugin_web_dir', [$this, 'getPluginWebDir']),
        ];
    }

    /**
     * Call plugin hook with given params.
     *
     * @param string  $name          Hook name.
     * @param mixed   $params        Hook parameters.
     * @param bool    $return_result Indicates that the result should be returned.
     *
     * @return mixed|void
     */
    public function callPluginHook(string $name, $params = null, bool $return_result = false)
    {
        $result = Plugin::doHook($name, $params);

        if ($return_result) {
            return $result;
        }
    }

    /**
     * Call plugin hook function with given params.
     *
     * @param string  $name          Hook name.
     * @param mixed   $params        Hook parameters.
     * @param bool    $return_result Indicates that the result should be returned.
     *
     * @return mixed|void
     */
    public function callPluginHookFunction(string $name, $params = null, bool $return_result = false)
    {
        $result = Plugin::doHookFunction($name, $params);

        if ($return_result) {
            return $result;
        }
    }

    /**
     * Call Plugin::getWebDir() with given params.
     *
     * @param string  $plugin
     * @param bool    $full
     * @param bool    $use_url_base
     *
     * @return string|null
     */
    public function getPluginWebDir(
        string $plugin,
        bool $full = true,
        bool $use_url_base = false
    ): ?string {
        return Plugin::getWebDir($plugin, $full, $use_url_base) ?: null;
    }
}
