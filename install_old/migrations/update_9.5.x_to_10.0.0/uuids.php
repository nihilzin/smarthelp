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

/** Add UUIDs */

$to_add_uuid = ['Monitor', 'NetworkEquipment', 'Peripheral', 'Phone', 'Printer'];

foreach ($to_add_uuid as $class) {
    $migration->addField($class::getTable(), 'uuid', 'string', [
        'after'  => 'is_dynamic',
        'null'   => true
    ]);
    $migration->addKey($class::getTable(), 'uuid');
}
