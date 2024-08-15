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

use Locale;
use Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @since 10.0.0
 */
class I18nExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('__', '__'),
            new TwigFunction('_n', '_n'),
            new TwigFunction('_x', '_x'),
            new TwigFunction('_nx', '_nx'),
            new TwigFunction('get_current_locale', [$this, 'getCurrentLocale']),
            new TwigFunction('get_plural_number', [Session::class, 'getPluralNumber']),
        ];
    }

    public function getCurrentLocale(): array
    {
        return Locale::parseLocale($_SESSION['glpilanguage'] ?? 'en_GB');
    }
}
