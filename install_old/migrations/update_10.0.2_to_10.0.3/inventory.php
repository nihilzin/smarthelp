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

use Glpi\Inventory\Conf;

/**
 * @var \Migration $migration
 */

//new right value for inventory
$migration->updateRight('inventory', READ | Conf::IMPORTFROMFILE | Conf::UPDATECONFIG, ['config' => UPDATE, 'inventory' => READ]);
