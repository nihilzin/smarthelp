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

/** @file
 * @brief
 */

/**
 * Update from 9.3.1 to 9.3.2
 *
 * @return bool for success (will die for most error)
 **/
function update931to932()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $current_config   = Config::getConfigurationValues('core');
    $updateresult     = true;
    $ADDTODISPLAYPREF = [];

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '9.3.2'));
    $migration->setVersion('9.3.2');

    /** Clean rack/enclosure items corrupted relations */
    $corrupted_criteria = [
        'OR' => [
            'itemtype' => 0,
            'items_id' => 0,
        ],
    ];
    $DB->deleteOrDie(Item_Rack::getTable(), $corrupted_criteria);
    $DB->deleteOrDie(Item_Enclosure::getTable(), $corrupted_criteria);
    /** /Clean rack/enclosure items corrupted relations */

   // limit state visibility for enclosures and pdus
    $migration->addField('glpi_states', 'is_visible_enclosure', 'bool', [
        'value' => 1,
        'after' => 'is_visible_rack'
    ]);
    $migration->addField('glpi_states', 'is_visible_pdu', 'bool', [
        'value' => 1,
        'after' => 'is_visible_enclosure'
    ]);
    $migration->addKey('glpi_states', 'is_visible_enclosure');
    $migration->addKey('glpi_states', 'is_visible_pdu');

   // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
