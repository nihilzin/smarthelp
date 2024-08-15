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

$iterator = $DB->request('glpi_configs', ['name' => 'lock_use_lock_item']);
$lock_use_lock_item = $iterator->current()['value'] ?? false;

if ($lock_use_lock_item) {
    $iterator = $DB->request('glpi_configs', ['name' => 'lock_item_list']);
    $lock_item_list = $iterator->current()['value'] ?? '';
    $lock_item_list = json_decode($lock_item_list);

    if (is_array($lock_item_list)) {
        foreach ($lock_item_list as $itemtype) {
            $migration->changeSearchOption($itemtype, 205, 207);
            $migration->changeSearchOption($itemtype, 206, 208);
        }
    }
}
