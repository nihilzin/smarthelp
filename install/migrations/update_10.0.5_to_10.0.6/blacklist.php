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

if (countElementsInTable(Blacklist::getTable(), ["type" => Blacklist::IP, "value" => "::1"]) === 0) {
    $migration->addPostQuery(
        $DB->buildInsert(
            'glpi_blacklists',
            [
                'name'      => 'IPV6 localhost',
                'value' => '::1',
                'type' => Blacklist::IP,
            ]
        )
    );
}
