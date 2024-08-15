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

use Search;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @since 10.0.0
 */
class SearchExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('showItem', [$this, 'showItem']),
        ];
    }

    public function showItem(
        int $displaytype,
        string $value = null,
        int $num = 0,
        int $row = 0,
        string $extraparams = ""
    ): string {
       // This is mandatory as Search::showItem expected third param to be passed by reference...
        return Search::showItem($displaytype, $value, $num, $row, $extraparams);
    }
}
