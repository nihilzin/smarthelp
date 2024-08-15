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

if (!$DB->fieldExists('glpi_softwarelicenses', 'ancestors_cache')) {
    $migration->addField('glpi_softwarelicenses', 'ancestors_cache', 'longtext');
}

if (!$DB->fieldExists('glpi_softwarelicenses', 'sons_cache')) {
    $migration->addField('glpi_softwarelicenses', 'sons_cache', 'longtext');
}
