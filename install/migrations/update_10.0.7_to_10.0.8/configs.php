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
$migration->addConfig([
    'use_flat_dropdowntree_on_search_result'   => 1,
]);
$migration->addField('glpi_users', 'use_flat_dropdowntree_on_search_result', 'tinyint DEFAULT NULL');
