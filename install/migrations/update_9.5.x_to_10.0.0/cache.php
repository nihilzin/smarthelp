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

$had_custom_config = false;
if (countElementsInTable('glpi_configs', ['name' => 'cache_db', 'context' => 'core'])) {
    $DB->deleteOrDie('glpi_configs', ['name' => 'cache_db', 'context' => 'core']);
    $had_custom_config = true;
}
if (countElementsInTable('glpi_configs', ['name' => 'cache_trans', 'context' => 'core'])) {
    $DB->deleteOrDie('glpi_configs', ['name' => 'cache_trans', 'context' => 'core']);
    $had_custom_config = true;
}

$migration->displayWarning(
    'GLPI cache has been changed and will not use anymore APCu or Wincache extensions. '
    . ($had_custom_config ? 'Existing cache configuration will not be reused. ' : '')
    . 'Use "php bin/console cache:configure" command to configure cache system.'
);
