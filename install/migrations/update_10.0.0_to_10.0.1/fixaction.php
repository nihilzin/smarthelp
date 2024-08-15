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

$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_rulecriterias',
        [
            'pattern' => $DB->escape('/(.*)[,|\/]/'),
        ],
        [
            'id' => 19,
            'pattern' => '/(.*),/',
        ]
    )
);
