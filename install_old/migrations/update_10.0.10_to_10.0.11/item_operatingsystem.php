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

$migration->addField('glpi_items_operatingsystems', 'company', "varchar(255) NULL DEFAULT NULL");
$migration->addField('glpi_items_operatingsystems', 'owner', "varchar(255) NULL DEFAULT NULL");
$migration->addField('glpi_items_operatingsystems', 'hostid', "varchar(255) NULL DEFAULT NULL");
