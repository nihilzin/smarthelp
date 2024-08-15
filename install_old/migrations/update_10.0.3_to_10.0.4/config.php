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

/**
 * @var \Migration $migration
 */

// CAS version config was missing on fresh installations since GLPI 9.4.0
$migration->addConfig(['cas_version' => 'CAS_VERSION_2_0']);

$should_inventory_be_enabled = 0; //default value
if (countElementsInTable(\Agent::getTable()) > 0) {
    $should_inventory_be_enabled = 1;
}
$migration->addConfig(['enabled_inventory' => $should_inventory_be_enabled], 'inventory');
