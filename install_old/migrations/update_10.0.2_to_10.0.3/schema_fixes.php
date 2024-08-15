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

// This key may be missing from database on GLPI instances that were migrated to 10.0 version
// prior to #9703 (so prior to 10.0.0-beta).
$migration->addKey('glpi_refusedequipments', 'autoupdatesystems_id');
