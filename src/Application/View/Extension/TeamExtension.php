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

use Glpi\Features\Teamwork;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @since 10.0.0
 */
class TeamExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('team_role_name', [$this, 'getTeamRoleName']),
        ];
    }

    public function getTeamRoleName($itemtype, int $role, int $nb = 1): string
    {
        if (\Toolbox::hasTrait($itemtype, Teamwork::class)) {
            return $itemtype::getTeamRoleName($role, $nb);
        }
        return '';
    }
}
