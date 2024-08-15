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

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

// `glpi_slas.calendars_id` will not exist if GLPI has been initialized on a 9.1.x version.
// Indeed, field was not present in `glpi-empty.sql` file, but was present in 0.90.x->9.1.0 migration.
// It has been added in `glpi-empty.sql` file on GLPI 9.2.0 (see commit ebd8c6f097fd0461e4f9840221224cbb5e89a7a1).
if (!$DB->fieldExists('glpi_slas', 'calendars_id')) {
    $migration->addField('glpi_slas', 'calendars_id', "int {$default_key_sign} NOT NULL DEFAULT 0");
    $migration->addKey('glpi_slas', 'calendars_id');
}

// Replace usage of negative values in `calendars_id` fields
foreach (['glpi_slms', 'glpi_olas', 'glpi_slas'] as $table) {
    if (!$DB->fieldExists($table, 'use_ticket_calendar')) {
        $migration->addField($table, 'use_ticket_calendar', 'bool');
        $migration->addPostQuery(
            $DB->buildUpdate(
                $table,
                [
                    'use_ticket_calendar' => 1,
                    'calendars_id'        => 0,
                ],
                [
                    'calendars_id'        => -1,
                ]
            )
        );
    }
}

// Copy calendar settings from SLM to children
foreach (['glpi_olas', 'glpi_slas'] as $table) {
    $migration->addPostQuery(
        $DB->buildUpdate(
            $table,
            [
                $table . '.use_ticket_calendar' => new QueryExpression($DB->quoteName('glpi_slms.use_ticket_calendar')),
                $table . '.calendars_id'        => new QueryExpression($DB->quoteName('glpi_slms.calendars_id')),
            ],
            [
                true
            ],
            [
                'INNER JOIN' => [
                    'glpi_slms' => [
                        'FKEY' => [
                            $table      => 'slms_id',
                            'glpi_slms' => 'id'
                        ],
                    ],
                ],
            ]
        )
    );
}
