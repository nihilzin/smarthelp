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

use Html;
use Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @since 10.0.0
 */
class RoutingExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('index_path', [$this, 'indexPath']),
            new TwigFunction('path', [$this, 'path']),
            new TwigFunction('url', [$this, 'url']),
        ];
    }

    /**
     * Return index path.
     *
     * @return string
     */
    public function indexPath(): string
    {
        $index = '/index.php';
        if (Session::getLoginUserID() !== false) {
            $index = Session::getCurrentInterface() == 'helpdesk'
            ? 'front/helpdesk.public.php'
            : 'front/central.php';
        }
        return Html::getPrefixedUrl($index);
    }

    /**
     * Return domain-relative path of a resource.
     *
     * @param string $resource
     *
     * @return string
     */
    public function path(string $resource): string
    {
        return Html::getPrefixedUrl($resource);
    }

    /**
     * Return absolute URL of a resource.
     *
     * @param string $resource
     *
     * @return string
     */
    public function url(string $resource): string
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        $prefix = $CFG_GLPI['url_base'];
        if (substr($resource, 0, 1) != '/') {
            $prefix .= '/';
        }

        return $prefix . $resource;
    }
}
