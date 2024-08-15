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

$migration->changeField('glpi_dashboards_items', 'card_id', 'card_id', 'varchar(255) NOT NULL');
$migration->changeField('glpi_dashboards_items', 'gridstack_id', 'gridstack_id', 'varchar(255) NOT NULL');
