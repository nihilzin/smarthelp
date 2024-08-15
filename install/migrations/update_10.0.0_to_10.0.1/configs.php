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

use Glpi\Agent\Communication\AbstractRequest;

$migration->displayMessage('Add new configurations / user preferences');
$migration->addPreQuery(
    $DB->buildUpdate(
        Config::getTable(),
        [
            'context' => 'inventory'
        ],
        [
            'name' => 'inventory_frequency',
            'context' => 'core'
        ]
    )
);
$migration->addConfig(
    ['inventory_frequency' => AbstractRequest::DEFAULT_FREQUENCY],
    'inventory'
);
