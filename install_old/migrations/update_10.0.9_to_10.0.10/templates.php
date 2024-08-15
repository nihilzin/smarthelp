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
 * @var \Migration $migration
 */

$rich_text_fields = [
    'glpi_itilfollowuptemplates'          => 'content',
    'glpi_planningexternaleventtemplates' => 'text',
    // already a longtext 'glpi_projecttasktemplates'           => 'description',
    'glpi_solutiontemplates'              => 'content',
    'glpi_tasktemplates'                  => 'content',
];
foreach ($rich_text_fields as $table => $field) {
    $migration->changeField(
        $table,
        $field,
        $field,
        'mediumtext',
    );
}
