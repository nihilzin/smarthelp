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

/* Add `event` to some glpi_queuednotifications */
if (!$DB->fieldExists('glpi_queuednotifications', 'event')) {
    $migration->addField('glpi_queuednotifications', 'event', 'varchar(255) DEFAULT NULL', ['value' => null]);
}
