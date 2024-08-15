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
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

$migration->displayMessage("Adding unicity key to reservationitem");
$table = 'glpi_reservationitems';

// Copy table
$tmp_table = "tmp_$table";
$migration->copyTable($table, $tmp_table, false);

// Drop is_deleted
$migration->dropKey($tmp_table, 'is_deleted');
$migration->dropField($tmp_table, "is_deleted");

// Add unicity key
$migration->addKey(
    $tmp_table,
    ['itemtype', 'items_id'],
    'unicity',
    'UNIQUE'
);

// Insert without duplicates
$quote_tmp_table = $DB->quoteName($tmp_table);
$select = $DB->request([
    'FROM' => $table
])->getSql();

// "IGNORE" keyword used to avoid duplicates
$DB->doQueryOrDie("INSERT IGNORE INTO $quote_tmp_table $select");

// Replace table with the new version
$migration->dropTable($table);
$migration->renameTable($tmp_table, $table);
