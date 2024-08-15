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

$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();
$migration->addField('glpi_changes', 'locations_id', "int {$default_key_sign} NOT NULL DEFAULT '0'");
$migration->addKey('glpi_changes', 'locations_id', 'locations_id');
