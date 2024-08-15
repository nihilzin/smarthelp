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


if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

 /* Add `sourceof_items_id` to some glpi_tickettasks */
if (!$DB->fieldExists('glpi_tickettasks', 'sourceof_items_id')) {
    $migration->addField('glpi_tickettasks', 'sourceof_items_id', "int {$default_key_sign} NOT NULL DEFAULT '0'", ['value' => 0]);
    $migration->addKey('glpi_tickettasks', 'sourceof_items_id');
}
