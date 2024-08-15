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

//lockedfield previous value must be null for global lock
$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_lockedfields',
        [
            'value' => null
        ],
        [
            'is_global' => 1
        ]
    )
);

//global lock on entities_id should not / no longer exist
$migration->addPostQuery(
    $DB->buildDelete(
        'glpi_lockedfields',
        [
            'is_global' => 1,
            'field' => 'entities_id'
        ]
    )
);
