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
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

if (!$DB->fieldExists(\Domain_Item::getTable(), 'is_dynamic')) {
    $migration->addField(
        \Domain_Item::getTable(),
        'is_dynamic',
        'bool'
    );
    $migration->addKey(
        \Domain_Item::getTable(),
        'is_dynamic'
    );

    $migration->addField(
        \Domain_Item::getTable(),
        'is_deleted',
        'bool'
    );
    $migration->addKey(
        \Domain_Item::getTable(),
        'is_deleted'
    );
}
