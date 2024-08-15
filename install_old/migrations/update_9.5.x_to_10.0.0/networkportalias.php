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

/** Replace -1 values for glpi_networkportaliases.networkports_id_alias field */
// Migration may have been missed if user installed 10.x version before 9.5.7 release date.
$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_networkportaliases',
        ['networkports_id_alias' => '0'],
        ['networkports_id_alias' => '-1']
    )
);
/** /Replace -1 values for glpi_networkportaliases.networkports_id_alias field */
