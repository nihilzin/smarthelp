<?php

/*!
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

$migration->addField('glpi_cartridgeitems', 'stock_target', 'int', [
    'value'  => 0,
    'after'  => 'alarm_threshold'
]);

$migration->addField('glpi_consumableitems', 'stock_target', 'int', [
    'value'  => 0,
    'after'  => 'alarm_threshold'
]);
