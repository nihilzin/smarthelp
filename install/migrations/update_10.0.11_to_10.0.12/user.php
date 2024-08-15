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

 // Add user_dn_hash field
$migration->addField('glpi_users', 'user_dn_hash', 'varchar(32)', [
    'after'  => 'user_dn',
]);

$migration->addPostQuery($DB->buildUpdate(
    'glpi_users',
    [
        'user_dn_hash' => new \QueryExpression('MD5(`user_dn`)'),
    ],
    [
        'NOT' => [
            'user_dn' => null
        ]
    ]
));

// Add user_dn_hash index
$migration->addKey('glpi_users', 'user_dn_hash');
