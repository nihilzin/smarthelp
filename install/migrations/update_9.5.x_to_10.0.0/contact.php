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


if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

if (!$DB->fieldExists("glpi_contacts", "registration_number")) {
    $migration->addField(
        "glpi_contacts",
        "registration_number",
        "string",
        [
            'after'     => "firstname",
        ]
    );
}
