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
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

$table = 'glpi_cables';
if (!$DB->fieldExists($table, 'is_deleted', false)) {
    $migration->addField($table, 'is_deleted', 'bool');
    $migration->addKey($table, 'is_deleted');
}

$migration->updateRight('cable_management', READ | UPDATE | CREATE | DELETE | PURGE, [
    'cable_management' => READ | UPDATE | CREATE | PURGE
]);
