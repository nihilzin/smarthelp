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

$migration->displayMessage('Add new configurations / user preferences');
$migration->addConfig(
    [
        'import_monitor' => 1,
        'import_printer' => 1,
        'import_peripheral' => 1
    ],
    'inventory'
);
