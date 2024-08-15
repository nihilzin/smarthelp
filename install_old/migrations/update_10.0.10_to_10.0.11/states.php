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
 * @var array $ADDTODISPLAYPREF
 */

if (!$DB->fieldExists('glpi_states', 'is_visible_unmanaged')) {
    $migration->addField('glpi_states', 'is_visible_unmanaged', 'bool', [
        'value' => 1,
        'after' => 'is_visible_cable'
    ]);
}
$migration->addKey('glpi_states', 'is_visible_unmanaged');
